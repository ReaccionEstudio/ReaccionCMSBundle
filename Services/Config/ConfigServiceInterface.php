<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Config;

	interface ConfigServiceInterface
	{
		public function get(String $key, Bool $loadFromCache = true);
		
		public function set(String $key, $value);
	}