<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Encryptor;

	interface EncryptorInterface
	{
		public function encrypt(String $content, String $password="");
		
		public function decrypt(String $content, String $password="");
	}