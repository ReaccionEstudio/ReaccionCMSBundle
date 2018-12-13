<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Helpers;

	final class HtmlAttributesHelper
	{
		/**
		 * @var Array
		 *
		 * Array with attributes
		 */
		private $attrs;

		/**
		 * Constructor
		 */
		public function __construct(Array $attrs)
		{
			$this->attrs = $attrs;
		}

		/**
		 * Convert array with attributes to string
		 *
		 * @return String 	$attributes 	Attributes as string
		 */
		public function getAttributesAsString() : String
		{
			$attributes = "";

			foreach($this->attrs as $key => $value)
			{
				if(empty($value)) continue;
				$attributes .= ' ' . $key . '="' . $value . '"';
			}

			return $attributes;
		}
	}