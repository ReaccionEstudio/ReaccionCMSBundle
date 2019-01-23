<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils;

	interface EncryptorInterface
	{
		public function encrypt(String $content, String $password="");
		
		public function decrypt(String $content, String $password="");
	}