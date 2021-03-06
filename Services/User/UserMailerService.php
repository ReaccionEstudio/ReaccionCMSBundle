<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\User;

	use FOS\UserBundle\Model\UserInterface;
	use FOS\UserBundle\Mailer\MailerInterface;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\MailerService;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

	/**
	 * Custom FOS User bundle mailer service.
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
	class UserMailerService implements MailerInterface
	{
		/**
		 * @var MailerService
		 *
		 * Mailer service
		 */
		private $mailer;

		/**
	     * @var UrlGeneratorInterface
	     */
	    protected $router;

		/**
		 * @var LoggerServiceInterface
		 *
		 * Logger service
		 */
		private $logger;

		/**
		 * @var Twig_Environment
		 *
		 * Twig
		 */
		private $twig;

		/**
		 * @var ConfigServiceInterface
		 *
		 * Config service
		 */
		private $config;

		/**
		 * @var String
		 *
		 * From email value
		 */
		private $fromEmail;

		/**
	     * @var array
	     */
	    protected $parameters;

		/**
		 * Constructor
	     *
	     * @param \Swift_Mailer         $mailer
	     * @param UrlGeneratorInterface $router
	     * @param \Twig_Environment     $twig
	     * @param array                 $parameters
	     */
		public function __construct(MailerService $mailer, UrlGeneratorInterface $router, LoggerServiceInterface $logger, \Twig_Environment $twig, ConfigServiceInterface $config, array $parameters)
		{
			$this->mailer 		= $mailer;
			$this->router 		= $router;
			$this->logger 		= $logger;
			$this->config   	= $config;
			$this->twig   		= $twig;
			$this->parameters   = $parameters;
			$this->fromEmail 	= $this->config->get("mailer_username");
		}

		/**
	     * Send an email to a user to confirm the account creation
	     *
	     * @param UserInterface $user
	     */
	    public function sendConfirmationEmailMessage(UserInterface $user) : Bool
	    {
	    	$template = $this->parameters['confirmation.template'];
	    	
	        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

	        $context = array(
	            'user' => $user,
	            'confirmationUrl' => $url,
	        );

	        return $this->sendMessage($template, $context, $this->fromEmail, (string) $user->getEmail());
	    }

	    /**
	     * Send an email to a user to confirm the password reset
	     *
	     * @param UserInterface $user
	     */
	    public function sendResettingEmailMessage(UserInterface $user) : Bool
	    {
	    	$template = $this->parameters['resetting.template'];
	        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

	        $context = array(
	            'user' => $user,
	            'confirmationUrl' => $url,
	        );

	        return $this->sendMessage($template, $context, $this->fromEmail, (string) $user->getEmail());
	    }

	    /**
	     * @param 	string $templateName
	     * @param 	array  $context
	     * @param 	array  $fromEmail
	     * @param 	string $toEmail
	     * @return 	boolean
	     */
	    protected function sendMessage($templateName, $context, $fromEmail, $toEmail) : Bool
	    {
	        $template = $this->twig->load($templateName);
	        $subject = $template->renderBlock('subject', $context);
	        $textBody = $template->renderBlock('body_text', $context);
	        $textBody = nl2br($textBody);

	        return $this->mailer->send([ $fromEmail ], [ $toEmail ], $subject, $textBody);
	    }
	}