<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;

	class ThemeService
	{
		CONST DEFAULT_CMS_THEME = 'rocket_theme';
		CONST DEFAULT_CMS_THEMES_PATH = 'templates/ReaccionCMSBundle/themes/';
		CONST DEFAULT_CMS_THEMES_TWIG_PATH = '@ReaccionCMSBundle';

		/**
		 * @var ConfigService
		 *
		 * Config service
		 */
		private $configService;

		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManagerInterface
		 */
		private $em;

		/**
		 * @var String
		 *
		 * Kernel project directory
		 */
		private $projectDir;

		/**
		 * @var String
		 * 
		 * Full template path
		 */		
		private $fullTemplatePath = "";

		/**
		 * @var String
		 * 
		 * Full template view file path
		 */
		private $fullTemplateViewPath = "";

		/**
		 * @var String
		 * 
		 * Current theme
		 */
		private $currentTheme = "";

		/**
		 * @var String
		 * 
		 * Themes path
		 */
		private $themesPath = "";

		/**
		 * @var String
		 * 
		 * Template view filename
		 */
		private $templateView = "";

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, ConfigService $configService, String $projectDir = "")
		{
			$this->em = $em;
			$this->configService = $configService;
			$this->projectDir = $projectDir;

			$this->getCurrentTheme();
			$this->getThemesPath();
			$this->generateFullTemplatePath();
		}

		/**
		 * Get full view path for Page entity
		 *
		 * @param  Page			$entity 	Current Page entity
		 * @return String 		[type]		View file path for Twig
		 */
		public function getPageViewPath(Page $page) : String
		{
			// check if view file exists
			if( ! $this->viewFileExists($page) )
			{
				// throw exception
				throw new \Exception('Template file not found in: ' . $this->fullTemplateViewPath);
			}

			// generate twig view file path
			return $this->generateRelativeTwigViewPath($this->templateView);
		}

		/**
		 * Get fullTemplateViewPath var value
		 *
		 * @return String 	$fullTemplateViewPath	View file path for Twig
		 */
		public function getFullTemplateViewPath() : String
		{
			return $this->fullTemplateViewPath;
		}

		/**
		 * Get fullTemplatePath var value
		 *
		 * @return String 	$fullTemplatePath	Current template path
		 */
		public function getFullTemplatePath() : String
		{
			return $this->fullTemplatePath;
		}

		/**
		 * Generate relative view path for Twig
		 *
		 * @param  String 	$baseFilename 	Template filename
		 * @return String 	$path 			Twig view relative path
		 */
		public function generateRelativeTwigViewPath(String $baseFilename) : String
		{
			$path = $this->themesPath;
			$path = str_replace("templates/", "", $path);
			$path .= $this->currentTheme . "/" . $baseFilename;

			return $path;
		}

		/** 
		 * Get theme config parameters
		 *
		 * @param  String 	$key 	Config parameter key
		 * @return String 	[type] 	Config parameter value
		 */
		public function getConfig(String $configKey = "")
		{
			// get view filename from theme config
			$themeConfigService = new ThemeConfigService($this->fullTemplatePath);
			$config = $themeConfigService->getConfig();

			if(empty($configKey)) 
			{
				return $config;
			}
			else if(strlen($configKey) && isset($config['theme_config'][$configKey]))
			{
				return $config['theme_config'][$configKey];
			}
		}

		/**
		 * Get config view parameter value
		 * 
		 * @param  String 	$key 								Config parameter key
		 * @param  Boolean 	$generateRelativeTwigViewPath		Get relative twig view path
		 * @return String 	$view 								View path
		 */
		public function getConfigView(String $key="", Bool $generateRelativeTwigViewPath = false) : String
		{
			$view = "";
			$configViews = $this->getConfig("views");

			if($configViews && strlen($key) && isset($configViews[$key]))
			{
				$view = $configViews[$key];
			}

			if($generateRelativeTwigViewPath == true)
			{
				$view = $this->generateRelativeTwigViewPath($view);
			}

			return $view;
		}

		/**
		 * Check if theme view file exists
		 *
		 * @param  Page 		$page			Current page entity
		 * @return Boolean 		true|false		File exists?
		 */
		private function viewFileExists(Page $page) : bool
		{
			if($page->getType() == "entry")
			{
				// get view filename from theme config
				$view = $this->getConfigView("entry");

				// set templateView value
				$this->templateView = $view ?? "entry.html.twig";
			}
			else
			{
				$this->templateView = $page->getTemplateView();
			}

			// set view file path
			$this->fullTemplateViewPath = $this->fullTemplatePath . $this->templateView;

			if(file_exists($this->fullTemplateViewPath)) 
			{
				return true;
			}

			return false;
		}

		/**
		 * Generate fullTemplatePath var value
		 */
		private function generateFullTemplatePath() : void
		{
			$this->fullTemplatePath = $this->projectDir . "/" . $this->themesPath . "/" . $this->currentTheme . "/";
		}

		/**
		 * Get current theme name defined in the configuration table
		 */
		private function getCurrentTheme() : void
		{
			$defaultThemeConfig = $this->configService->get("current_theme");
			$this->currentTheme = ($defaultThemeConfig) ? $defaultThemeConfig : self::DEFAULT_CMS_THEME; 
		}

		/**
		 * Get current themes path defined in the configuration table
		 */
		private function getThemesPath() : void
		{
			$themesPathConfig = $this->configService->get("themes_path");
			$this->themesPath = ($themesPathConfig) ? $themesPathConfig : self::DEFAULT_CMS_THEMES_PATH;
		}
	}