<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Encryptor;

	use Defuse\Crypto\Crypto;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Encryptor\EncryptorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

	/**
	 * Encryptor - Simple Encryption in PHP
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class Encryptor implements EncryptorInterface
	{
		/**
		 * @var LoggerServiceInterface
		 *
		 * Logger
		 */
		private $logger;

		/**
		 * @var String 
		 *
		 * Default encryption key
		 */
		private $encryption_key;

		/**
		 * Constructor
		 */
		public function __construct(LoggerServiceInterface $logger, String $encryption_key)
		{
			$this->logger = $logger;
			$this->encryption_key = $encryption_key;
		}

		/**
		 * Encrypts a string value
		 *
		 * @param  String 	$content 	String value
		 * @param  String 	$password 	Encryption password
		 * @return String 	[type]		Encrypted value
		 */
		public function encrypt(String $content, String $password="") : String
		{
			try
			{
				$password = ($password) ?? $this->encryption_key;
				return Crypto::encryptWithPassword($content, $password);
			}
			catch(\Exception $e)
			{
				$this->logger->logException($e, "Method: Encryptor::encrypt()");
				return '';
			}
		}

		/**
		 * Decrypts a string value
		 *
		 * @param  String 	$content 	String value
		 * @param  String 	$password 	Encryption password
		 * @return String 	[type]		Encrypted value
		 */
		public function decrypt(String $content, String $password="") : String
		{
			try
			{
				$password = ($password) ?? $this->encryption_key;
				return Crypto::decryptWithPassword($content, $password);
			}
			catch(\Exception $e)
			{
				$this->logger->logException($e, "Method: Encryptor::encrypt()");
				return '';
			}
		}
	}