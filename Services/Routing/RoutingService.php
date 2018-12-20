<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Routing;

	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Cache\PageCacheService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Cache\CacheService;

	/**
	 * ReaccionCMSBundle routing service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class RoutingService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var ThemeService
		 *
		 * Theme service
		 */
		private $theme;

		/**
		 * @var Twig_Environment
		 *
		 * Twig
		 */
		private $twig;

		/**
		 * @var CacheService
		 *
		 * Cache service
		 */
		private $cacheService;

		/**
		 * Constructor
		 */
		public function __construct(EntityManager $em, ThemeService $theme, PageCacheService $pageCache, \Twig_Environment $twig, CacheService $cacheService)
		{
			$this->em 		 = $em;
			$this->twig 	 = $twig;
			$this->theme 	 = $theme;
			$this->pageCache = $pageCache;
			$this->cacheService = $cacheService;
		}

		public function load(String $slug="")
		{
			$viewFile = "";
			$entry 	  = null;

			// Load page by slug or main page if slug is empty
			$page = (strlen($slug)) 
					? $this->pageCache->getPageBySlug($slug)
					: $this->pageCache->getMainPage();

			if($page)
			{
				// get page view file
				$pageEntity = (new Page())->setTemplateView($page['templateView']);
				$viewFile = $this->theme->getPageViewPath($pageEntity);
			}
			else
			{
				// it is a entry slug?
				
			}

			if( ! $page && $entry == null)
			{
				// load 404 error page
				return $this->loadErrorPage(404, $slug);
			}

			// Load default ReaccionCMS home page
			if( ! strlen($viewFile) )
			{
				$viewFile = "@ReaccionCMSBundle/index.html.twig";
				$page 	  = ['cmsVersion' => 0.1];
			}

			return $this->twig->render($viewFile, $page);
		}

		/**
		 * Load entry searching by slug
		 *
		 * @param  String 	$slug 			Route slug
		 * @return Array 	$entryResult 	Found entry entity
		 */
		public function loadEntry(String $slug="")
		{
			$entryResult = [];

			if(strlen($slug))
			{
				$entry = $this->searchEntryBySlug($slug);
			}

			if($entry !== null)
			{
				$templateView = $this->theme->getPageViewPath($entry);

				$entryResult = [
					'entry' => $entry, 
					'view'  => $templateView
				];
			}

			return $entryResult;
		}

		/**
		 * Load error page by error number
		 *
		 * @param  Integer 	$errorNumber 	Error number
		 * @param  String 	$slug 			Slug
		 * @return String 	[type] 			View rendered as HTML
		 */
		public function loadErrorPage(Int $errorNumber, String $slug) : String
		{
			$fullTemplatePath = $this->theme->getFullTemplatePath();
			
			// Get theme config file
			$themeConfigService = new ThemeConfigService($fullTemplatePath);
			$config = $themeConfigService->getConfig();

			if(isset($config['theme_config']['views'][$errorNumber . '_error']))
			{
				$baseFilename = $config['theme_config']['views'][$errorNumber . '_error'];
				$view = $this->theme->generateRelativeTwigViewPath($baseFilename);

				return $this->twig->render($view, [ 'slug' => $slug ]);
			}

			return '';
		}

		/**
		 * Search entry entity by slug
		 *
		 * @param  String 			$slug 	Route slug
		 * @return Entry | null 	[type] 	Found entry entity
		 */
		private function searchEntryBySlug(String $slug)
		{
			return $this->em->getRepository(Entry::class)->findOneBy(
				[
					'slug' => $slug,
					'enabled' => true
				]
			);
		}
	}