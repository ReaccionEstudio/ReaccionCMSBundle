<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Session\Session;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\LoggerService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Email\EmailTemplateService;

	class MailerService
	{
		/**
		 * @var LoggerService
		 *
		 * Logger
		 */
		private $logger;

		/**
		 * @var ConfigService
		 *
		 * Config
		 */
		private $config;

		/**
		 * @var Session
		 *
		 * Session
		 */
		private $session;

		/**
		 * @var Swift_SmtpTransport
		 *
		 * Swift_SmtpTransport
		 */
		private $mailer;

		/**
		 * @var String
		 *
		 * SMTP host
		 */
		private $host;

		/**
		 * @var Integer
		 *
		 * SMTP port
		 */
		private $port = 25;

		/**
		 * @var String
		 *
		 * SMTP authentication type
		 */
		private $auth = '';

		/**
		 * @var Username
		 *
		 * SMTP username
		 */
		private $username;

		/**
		 * @var String
		 *
		 * SMTP password
		 */
		private $password;

		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManagerInterface
		 */
		private $em;

		/**
		 * Constructor
		 */
		public function __construct(LoggerService $logger, ConfigService $config, Session $session, EntityManagerInterface $em, EmailTemplateService $emailTemplate)
		{
			$this->em 			 = $em;
			$this->logger   	 = $logger;
			$this->config   	 = $config;
			$this->session  	 = $session;
			$this->emailTemplate = $emailTemplate;
		}

		/**
		 * Send email message
		 *
		 * @param  Array  	$from 		Sender
		 * @param  Array  	$to 		Receiver
		 * @param  String 	$subject 	Subject
		 * @param  String 	$body 		HTML message body
		 * @return Boolean 	$result  	Email sent result
		 */
		public function send(Array $from = [], Array $to = [], String $subject = "", String $body = "") : Bool
		{
			$this->getSmtpTransport();

			$message = (new \Swift_Message($subject))
						  ->setFrom($from)
						  ->setTo($to)
						  ->setBody($body, 'text/html');

			$result = $this->mailer->send($message);

			// log email result
			if($result)
			{
				$this->logger->addInfo("Email sent from: " . implode(", ", $from) . " to: " . implode(", ", $to) . " with subject: " . $subject);
			}

			return $result;
		}

		/**
		 * Send email using a defined email
		 * 
		 * @param  String 	$slug 				Email slug
		 * @param  Array  	$to 				Email receiver
		 * @return Boolean 	$result  			Email sent result
		 */
		public function sendTemplate(String $slug, Array $to = []) : Bool
		{
			if(empty($to)) return false;

			$message 	= $this->emailTemplate->loadTemplate($slug)->getBodyHtml();
			$emailData 	= $this->emailTemplate->getEmailTemplateData();
			
			// From
			if( ! empty($emailData['fromname']) && ! empty($emailData['fromemail']) )
			{
				$from = [ $emailData['fromemail'] => $emailData['fromname'] ];
			}
			else if( ! empty($emailData['fromemail']) )
			{
				$from = [ $emailData['fromemail'] ];
			}
			else if( empty($emailData['fromemail']) )
			{
				$from = [ $this->username ];
			}

			if(empty($message))
			{
				$this->logger->addError("Error sending email template with '" . $slug . "' slug: Email body message variable is empty.");
				return false;
			}

			// send email
			return $this->send($from, $to, $emailData['subject'], $message);
		}

		/**
		 * Send test email
		 *
		 * @return Boolean 	true|false
		 */
		public function sendTestEmail() : Bool
		{
			try
			{
				$this->getSmtpTransport();

				$subject = 'ReaccionCMS test email';

				// Get all administrators emails
				$adminEmails = $this->em->getRepository(User::class)->getAdminEmailAdresses();

				foreach($adminEmails as $email)
				{
					$username = $email['nickname'] ?? $email['username'];
					$to = [ $email['email'] => $username ];
					
					$message = (new \Swift_Message($subject))
							  ->setFrom([ $this->username => 'ReaccionCMS'])
							  ->setTo($to)
							  ->setBody('Your email settings were saved correctly!', 'text/html');

					$result = $this->mailer->send($message);

					// log email result
					if($result)
					{
						$this->logger->addInfo("Test email sent to: " . implode(", ", $to) . " with subject: " . $subject);
					}
				}

				return $result;
			}
			catch(\Exception $e)
			{
				$this->logger->logException($e);
				$this->session->getFlashBag()->add('warning', $e->getMessage());
				return false;				
			}
		}

		/**
		 * Test email settings sending a test email.
		 *
		 * @param  String 	$host 		SMTP host
		 * @param  String 	$port 		SMTP port
		 * @param  String 	$auth 		SMTP authentication
		 * @param  String 	$username 	SMTP username
		 * @param  String 	$password 	SMTP password
		 * @return Boolean	[type]
		 */
		public function testConnection(String $host, Int $port = 25, String $auth = '', String $username, String $password) : Bool
		{
			$this->host = $host;
			$this->port = $port;
			$this->auth = $auth;
			$this->username = $username;
			$this->password = $password;

			$this->getSmtpTransport();
			return $this->sendTestEmail();
		}

		/**
		 *  Load mailer configuration parameters from config entities
		 */
		private function loadMailerConfig() : void
		{
			$this->host = $this->config->get("mailer_host", false);
			$this->port = $this->config->get("mailer_port", false);
			$this->username = $this->config->get("mailer_username", false);
			$this->password = $this->config->get("mailer_password", false);
			$this->auth = $this->config->get("mailer_authentication", false);
		}

		/**
		 * Get SMTP transport object
		 */
		private function getSmtpTransport() : void
		{
			try
			{
				if(empty($this->host)) $this->loadMailerConfig();

				$this->mailer = (new \Swift_SmtpTransport($this->host, $this->port, $this->auth))
										->setUsername($this->username)
										->setPassword($this->password);

				// tls, ssl
				if($this->auth == "tls")
				{
					$this->mailer->setStreamOptions(['ssl' => ['allow_self_signed' => true, 'verify_peer' => false] ]);
				}
			}
			catch(\Exception $e)
			{
				$this->logger->logException($e);
				$this->session->getFlashBag()->add('warning', $e->getMessage());
			}
		}
	}