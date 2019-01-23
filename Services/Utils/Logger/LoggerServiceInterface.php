<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger;

	interface LoggerServiceInterface
	{
		public function logException(\Exception $exception, String $message = "" );
		
		public function addInfo(String $message);
		
		public function addError(String $message);
	}