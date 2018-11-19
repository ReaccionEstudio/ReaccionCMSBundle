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
		 * Constructor
		 */
		public function __construct(EntityManager $em, String $projectDir)
		{
			$this->em = $em;
			$this->projectDir = $projectDir;
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

			// get required config parameters
			$currentTheme = $this->getCurrentTheme();
			$themesPath = $this->getThemesPath();

			// check if view file exists
			if( ! $this->pageViewFileExists($currentTheme, $themesPath, $page) )
			{
				// throw exception
				throw new \Exception('Template file not found in: ' . $this->fullTemplateViewPath);
			}

			// generate twig view file path
			if($currentTheme == self::DEFAULT_CMS_THEME)
			{

				$path = self::DEFAULT_CMS_THEMES_TWIG_PATH . "/" . 
						self::DEFAULT_CMS_THEME . "/" . 
						$page->getTemplateView();
			}
			else
			{
				$path = self::DEFAULT_CMS_THEMES_PATH;
				$path = str_replace("templates/", "", $path);
				$path .= $currentTheme . "/" . $page->getTemplateView();
			}

			return $path;
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
		 * Check if theme view file exists
		 *
		 * @param  String 	$currentTheme 	Current theme
		 * @param  String 	$themesPath 	Current themes path
		 * @param  Page 	$page 			Current Page entity
		 * @return Boolean 	true|false		File exists?
		 */
		private function pageViewFileExists(String $currentTheme, String $themesPath , Page $page) : bool
		{
			if($currentTheme == self::DEFAULT_CMS_THEME)
			{
				$this->fullTemplatePath 	= $this->projectDir . "/" . self::ROCKET_THEME_PATH . "/";
				$this->fullTemplateViewPath = $this->fullTemplatePath . $page->getTemplateView();
			}
			else
			{
				$this->fullTemplatePath 	= $this->projectDir . "/" . $themesPath . "/" . $currentTheme . "/";
				$this->fullTemplateViewPath = $this->fullTemplatePath . $page->getTemplateView();
			}

			if(file_exists($this->fullTemplateViewPath)) 
			{
				return true;
			}

			return false;
		}

		/**
		 * Get current theme name defined in the configuration table
		 *
		 * @return 	String 	[type] 	Current theme
		 */
		private function getCurrentTheme() : String
		{
			$defaultThemeConfig = 	$this->em->getRepository(Configuration::class)->findOneBy(
										[ 'name' => 'current_theme' ]
									);

			if($defaultThemeConfig)
			{
				return $defaultThemeConfig->getValue();
			}

			return self::DEFAULT_CMS_THEME;
		}

		/**
		 * Get current themes path defined in the configuration table
		 *
		 * @return 	String 	[type] 	Current themes path
		 */
		private function getThemesPath() : String
		{
			$themesPathConfig = $this->em->getRepository(Configuration::class)->findOneBy(
										[ 'name' => 'themes_path' ]
									);

			if($themesPathConfig)
			{
				return $themesPathConfig->getValue();
			}

			return self::DEFAULT_CMS_THEMES_PATH;
		}
	}