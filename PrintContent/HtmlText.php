<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PrintContent;

	use App\ReaccionEstudio\ReaccionCMSBundle\PrintContent\PrintContentInterface;

	class HtmlText implements PrintContentInterface
	{
		/**
		 * Constructor
		 *
		 * @param String  $contentValue  Content value
		 */
		public function __construct(String $contentValue)
		{
			$this->contentValue = $contentValue;
		}

		/**
		 * Return content value
		 *
		 * @return String 	$this->contentValue    Content value
		 */
		public function getValue() : String
		{
			// TODO ...
			return $this->contentValue;
		}
	}