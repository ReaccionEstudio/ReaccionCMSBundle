<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes;

	use Symfony\Component\Yaml\Yaml;
	use Symfony\Component\Yaml\Exception\ParseException;

	class ThemeConfigService
	{
		/**
		 * @var String
		 * 
		 * Full template path
		 */		
		private $fullTemplatePath = "";

		/**
		 * @var Array 
		 * 
		 * Theme config file parameters
		 */
		private $configFile = array();

		/**
		 * Constructor
		 */
		public function __construct(String $fullTemplatePath)
		{
			$this->fullTemplatePath = $fullTemplatePath;
		}

		/**
		 * Load template config.yaml file
		 *
		 * @return Array 	[type] 		Config file parameters
		 */
		public function loadConfigFile() : Array
		{
			$configFilePath = $this->fullTemplatePath . "/config.yaml";

			if( ! file_exists($configFilePath) )
			{
				throw new \Error("File '" . $configFilePath . "' not found.");
			}

			return Yaml::parseFile($configFilePath);
		}

	}