<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PrintContent;

	interface PrintContentInterface
	{
		public function getValue() : String;
	}