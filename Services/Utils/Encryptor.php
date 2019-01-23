<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils;

	use Defuse\Crypto\Crypto;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\LoggerService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\EncryptorInterface;

	/**
	 * Encryptor - Simple Encryption in PHP
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class Encryptor implements EncryptorInterface
	{
		/**
		 * @var LoggerService
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
		public function __construct(LoggerService $logger, String $encryption_key)
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