<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Themes;

	use Symfony\Component\Yaml\Yaml;
	use Symfony\Component\Yaml\Exception\ParseException;

	/**
	 * Read theme file config for given template path
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
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
 		 * @var Array
		 *
		 * Theme config parameters
		 */
		private $config = array();

		/**
		 * Constructor
		 */
		public function __construct(String $fullTemplatePath)
		{
			$this->fullTemplatePath = $fullTemplatePath;
			$this->loadConfigFile();
		}

		/**
		 * Load template config.yaml file
		 *
		 * @return ThemeConfigService 	$this 		ThemeConfigService instance
		 */
		public function loadConfigFile() : ThemeConfigService
		{
			$configFilePath = $this->fullTemplatePath . "/config.yaml";

			if( ! file_exists($configFilePath) )
			{
				throw new \Error("File '" . $configFilePath . "' not found.");
			}

			$this->config = Yaml::parseFile($configFilePath);

			return $this;
		}

		/**
		 * Get available views from theme config file
		 *
		 * @return Array 	$views 		Available views
		 */
		public function getViews() : Array
		{
			$views = [];

			if(isset($this->config['theme_config']['views']))
			{
				$views = $this->config['theme_config']['views'];
			}

			return $views;
		}

		/**
		 * Return theme config file parameters
		 *
		 * @return 	Array 	$this->config 	Theme config parameters
		 */
		public function getConfig() : Array
		{
			return $this->config;
		}

	}