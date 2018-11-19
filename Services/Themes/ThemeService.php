<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes;

	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;

	class ThemeService
	{
		CONST DEFAULT_CMS_THEME = 'rocket_theme';
		CONST ROCKET_THEME_PATH = 'src/ReaccionEstudio/ReaccionCMSBundle/Resources/views/' . self::DEFAULT_CMS_THEME;
		CONST DEFAULT_CMS_THEMES_PATH = 'templates/ReaccionCMSBundle/themes/';
		CONST DEFAULT_CMS_THEMES_TWIG_PATH = '@ReaccionCMSBundle';

		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
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
		 * Constructor
		 */
		public function __construct(EntityManager $em, String $projectDir)
		{
			$this->em = $em;
			$this->projectDir = $projectDir;

			$this->getCurrentTheme();
			$this->getThemesPath();
			$this->generateFullTemplatePath();
		}

		/**
		 * Get full view path for Page entity
		 *
		 * @param  Page 	$page 		Current Page entity
		 * @return String 	[type]		View file path for Twig
		 */
		public function getPageViewPath(Page $page) : String
		{
			$path = '';

			// check if view file exists
			if( ! $this->pageViewFileExists($page) )
			{
				// throw exception
				throw new \Exception('Template file not found in: ' . $this->fullTemplateViewPath);
			}

			// generate twig view file path
			return $this->generateRelativeTwigViewPath($page->getTemplateView());
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
			if($this->currentTheme == self::DEFAULT_CMS_THEME)
			{
				$path = self::DEFAULT_CMS_THEMES_TWIG_PATH . "/" . 
						self::DEFAULT_CMS_THEME . "/" . 
						$baseFilename;
			}
			else
			{
				$path = $this->themesPath;
				$path = str_replace("templates/", "", $path);
				$path .= $this->currentTheme . "/" . $baseFilename;
			}

			return $path;
		}

		/**
		 * Check if theme view file exists
		 *
		 * @param  Page 	$page 			Current Page entity
		 * @return Boolean 	true|false		File exists?
		 */
		private function pageViewFileExists(Page $page) : bool
		{
			$this->fullTemplateViewPath = $this->fullTemplatePath . $page->getTemplateView();

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
			if($this->currentTheme == self::DEFAULT_CMS_THEME)
			{
				$this->fullTemplatePath = $this->projectDir . "/" . self::ROCKET_THEME_PATH . "/";
			}
			else
			{
				$this->fullTemplatePath = $this->projectDir . "/" . $this->themesPath . "/" . $this->currentTheme . "/";
			}
		}

		/**
		 * Get current theme name defined in the configuration table
		 */
		private function getCurrentTheme() : void
		{
			$defaultThemeConfig = 	$this->em->getRepository(Configuration::class)->findOneBy(
										[ 'name' => 'current_theme' ]
									);

			if($defaultThemeConfig)
			{
				$this->currentTheme = $defaultThemeConfig->getValue();
			}

			$this->currentTheme = self::DEFAULT_CMS_THEME;
		}

		/**
		 * Get current themes path defined in the configuration table
		 */
		private function getThemesPath() : void
		{
			$themesPathConfig = $this->em->getRepository(Configuration::class)->findOneBy(
										[ 'name' => 'themes_path' ]
									);

			if($themesPathConfig)
			{
				$this->themesPath = $themesPathConfig->getValue();
			}

			$this->themesPath = self::DEFAULT_CMS_THEMES_PATH;
		}
	}