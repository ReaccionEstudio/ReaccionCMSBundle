<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils;

	use Defuse\Crypto\Crypto;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\LoggerService;

	/**
	 * Encryptor - Simple Encryption in PHP
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class Encryptor
	{
		/**
		 * @var LoggerService
		 *
		 * Logger
		 */
		private $logger;

		/**
		 * Constructor
		 */
		public function __construct(LoggerService $logger)
		{
			$this->logger = $logger;
		}

		/**
		 * Encrypts a string value
		 *
		 * @param  String 	$content 	String value
		 * @param  String 	$password 	Encryption password
		 * @return String 	[type]		Encrypted value
		 */
		public function encrypt(String $content, String $password) : String
		{
			try
			{
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
		public function decrypt(String $content, String $password) : String
		{
			try
			{
				return Crypto::decryptWithPassword($content, $password);
			}
			catch(\Exception $e)
			{
				$this->logger->logException($e, "Method: Encryptor::encrypt()");
				return '';
			}
		}
	}