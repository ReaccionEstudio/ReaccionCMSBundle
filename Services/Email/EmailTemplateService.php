<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Email;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Language\LanguageService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

	/**
	 * Email template service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class EmailTemplateService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var LoggerServiceInterface
		 *
		 * Logger
		 */
		private $logger;

		/**
		 * @var Array
		 *
		 * Email template data
		 */
		private $emailTemplateData = [];

		/**
		 * @var Array
		 *
		 * Email Custom params
		 */
		private $customParams = [];

		/**
		 * @var String
		 *
		 * Email body HTML
		 */
		private $bodyHtml = '';

		/**
		 * @var Twig_Environment
		 *
		 * Twig
		 */
		private $twig;

		/**
		 * @var ConfigService
		 *
		 * Config
		 */
		private $config;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, LoggerServiceInterface $logger, LanguageService $language, \Twig_Environment $twig, ConfigService $config, String $projectDir)
		{
			$this->em 				= $em;
			$this->twig 	 		= $twig;
			$this->logger 			= $logger;
			$this->config 			= $config;
			$this->language 		= $language;
			$this->projectDir 		= $projectDir;
			$this->currentLanguage 	= $this->language->getLanguage();
		}

		/**
		 * Get email body HTML
		 *
		 * @return String 	$this->bodyHtml 	Email body HTML
		 */
		public function getBodyHtml() : String
		{
			return $this->bodyHtml;
		}

		/**
		 * Get email template data array
		 *
		 * @return Array 	$this->emailTemplateData 	Email template data
		 */
		public function getEmailTemplateData() : Array
		{
			return $this->emailTemplateData;
		}

		/**
		 * Load email template data
		 *
		 * @param  String 		$slug 				EmailTemplate slug
		 * @return self 		[type] 		
		 */
		public function loadTemplate(String $slug) : self
		{
			$emailTemplateEntity = $this->loadTemplateEntity($slug);
			
			if(empty($emailTemplateEntity) && $this->currentLanguage != "en")
			{
				$emailTemplateEntity = $this->loadTemplateEntity($slug, "en");
			}

			if(empty($emailTemplateEntity))
			{
				$this->logger->addError("Email template with slug '" . $slug . "' and language '[" . $this->currentLanguage . ", en]' not found.");
				return $this;
			}

			// Generate email view vars
			$this->generateEmailViewVars($emailTemplateEntity);

			// Email template data
			$this->emailTemplateData = [
				'name' => $emailTemplateEntity->getName(),
				'subject' => $emailTemplateEntity->getSubject(),
				'fromname' => $emailTemplateEntity->getFromname(),
				'fromemail' => $emailTemplateEntity->getFromemail(),
				'template_file' => $emailTemplateEntity->getTemplateFile(),
				'plain_text' => $emailTemplateEntity->isPlainText(),
				'enabled' => $emailTemplateEntity->isEnabled()
			];

			$this->bodyHtml = $this->getTemplateFileHtml($this->emailTemplateData['template_file']);

			return $this;
		}

		/**
		 * Generate email view vars
		 */
		private function generateEmailViewVars(EmailTemplate $emailTemplateEntity) : void
		{
			// custom params
			$customParams = json_decode($emailTemplateEntity->getMessageParams(), true);

			if( ! empty($customParams))
			{
				foreach($customParams as $param)
				{
					$this->customParams[$param['name']] = $param['value'];
				}	
			}

			// add message in the custom params array
			$this->customParams['message'] = $emailTemplateEntity->getMessage();
		}

		/**
		 * Get template file HTML code
		 *
		 * @param  String 	$file 				Template view filename
		 * @return String 	$emailFileHtml 		Email view file HTML
		 */
		private function getTemplateFileHtml(String $file) : String
		{
			$emailFileHtml = "";

			// get full email template
			$emailTemplatesPath = $this->projectDir;
			$emailTemplatesPath .= "/" . $this->config->get("email_templates_path") . $file;

			if( ! file_exists($emailTemplatesPath) )
			{
				$this->logger->addError("Email template view file '" . $emailTemplatesPath . "' not found.");
				return '';
			}

			// get relative email template
			$relativeEmailTemplatesPath = explode("templates/", $emailTemplatesPath);
			$relativeEmailTemplatesPath = $relativeEmailTemplatesPath[1];

			// get email file html
			$emailFileHtml = $this->twig->render($relativeEmailTemplatesPath, $this->customParams);

			return $emailFileHtml;
		}

		/**
		 * Get EmailTemplate entity from database
		 *
		 * @param  String 				$slug 		EmailTemplate slug
		 * @param  String 				$language 	EmailTemplate language
		 * @return EmailTemplate|null 	[type]		EmailTemplate entity
		 */
		private function loadTemplateEntity(String $slug, String $language = "")
		{
			$language = (strlen($language)) ? $language : $this->currentLanguage;

			$entityFilters = [ 'slug' => $slug, 'language' => $language ];
			return $this->em->getRepository(EmailTemplate::class)->findOneBy($entityFilters);
		}
	}