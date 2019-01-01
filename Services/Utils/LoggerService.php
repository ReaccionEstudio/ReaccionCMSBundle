<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils;

	use Symfony\Bridge\Monolog\Logger;

	class LoggerService
	{
		/**
		 * @var Logger
		 *
		 * Monolog logger
		 */
		private $monolog;

		/**
		 * Constructor
		 */
		public function __construct(Logger $monolog)
		{
			$this->monolog = $monolog;
		}

		/**
		 * Add exception log
		 *
		 * @param  Exception 	$exception 	 Exception object
		 * @return void 		[type]
		 */
		public function logException(\Exception $exception)
		{
			$message = "[ERROR] " . $exception->getMessage() . 
					 ". [TRACE] " . $exception->getTraceAsString();
					 
			$this->monolog->addError($message);
		}
	}