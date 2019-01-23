<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger;

	use Psr\Log\LoggerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

	/**
	 * Logger service - Handles application logs
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
	class LoggerService implements LoggerServiceInterface
	{
		/**
		 * @var LoggerInterface
		 *
		 * Logger
		 */
		private $monolog;

		/**
		 * Constructor
		 */
		public function __construct(LoggerInterface $monolog)
		{
			$this->monolog = $monolog;
		}

		/**
		 * Add exception log
		 *
		 * @param  Exception 	$exception 	 Exception object
		 * @param  String 		$message 	 Initial log message
		 * @return void 		[type]
		 */
		public function logException(\Exception $exception, String $message = "" )
		{
			$message .= " [ERROR] " . $exception->getMessage() . 
					 ". [TRACE] " . $exception->getTraceAsString();
					 
			$this->monolog->addError($message);
		}

		/**
 		 * Add info message to current log
 		 *
 		 * @param  String 	$message 	Log message
 		 * @return void 	[type]
		 */
		public function addInfo(String $message)
		{
			$this->monolog->addInfo($message);
		}

		/**
 		 * Add error message to current log
 		 *
 		 * @param  String 	$message 	Log message
 		 * @return void 	[type]
		 */
		public function addError(String $message)
		{
			$this->monolog->addError($message);
		}
	}