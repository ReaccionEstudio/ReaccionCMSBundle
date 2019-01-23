<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Session\Session;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Email\EmailTemplateService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Encryptor\EncryptorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

	class MailerService
	{
		/**
		 * @var LoggerServiceInterface
		 *
		 * Logger
		 */
		private $logger;

		/**
		 * @var ConfigServiceInterface
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
		 * @var EncryptorInterface
		 *
		 * Encryptor service
		 */
		private $encryptor;

		/**
		 * Constructor
		 */
		public function __construct(LoggerServiceInterface $logger, ConfigServiceInterface $config, Session $session, EntityManagerInterface $em, EmailTemplateService $emailTemplate, EncryptorInterface $encryptor)
		{
			$this->em 			 = $em;
			$this->logger   	 = $logger;
			$this->config   	 = $config;
			$this->session  	 = $session;
			$this->encryptor 	 = $encryptor;
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

				// Get all administrators emails
				$adminEmails = $this->em->getRepository(User::class)->getAdminEmailAdresses();

				foreach($adminEmails as $email)
				{
					$username = $email['nickname'] ?? $email['username'];
					$to = [ $email['email'] => $username ];
					
					$result = $this->sendTemplate("test-email", $to);

					// log email result
					if($result)
					{
						$this->logger->addInfo("Test email sent to: " . implode(", ", $to) . ".");
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
			$this->auth = $this->config->get("mailer_authentication", false);
			$this->username = $this->config->get("mailer_username", false);
			
			// Get password and decrypt it
			$password = $this->config->get("mailer_password", false);
			$this->password = $this->encryptor->decrypt($password);
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