<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Routing;

	use Doctrine\ORM\EntityManagerInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use ReaccionEstudio\ReaccionCMSBundle\Constants\ReaccionCMS;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeService;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Page\PageCacheService;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Cache\CacheServiceInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Routing\RoutingPageCacheData;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Routing\RoutingPageViewPathAdapter;

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
		 * EntityManagerInterface
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
		 * @var CacheServiceInterface
		 *
		 * Cache service
		 */
		private $cacheService;

		/**
		 * Constructor
		 */
		public function __construct(
		    EntityManagerInterface $em,
            ThemeService $theme,
            PageCacheService $pageCache,
            \Twig_Environment $twig,
            CacheServiceInterface $cacheService)
		{
			$this->em 		 = $em;
			$this->twig 	 = $twig;
			$this->theme 	 = $theme;
			$this->pageCache = $pageCache;
			$this->cacheService = $cacheService;
		}

		/**
		 * Load route by slug
		 *
		 * @param  String 	$slug 	Route slug
		 * @return String 	[type]  HTML page view to render
		 */
		public function load(string $slug="") : string
		{
			// get page data from cache
			$routingPageCacheData = new RoutingPageCacheData($slug, $this->pageCache);
			$page = $routingPageCacheData->getPageCacheData();
			
			if( ! $page)
			{	
				$totalExistingPages = $this->em->getRepository(Page::class)->getTotalPages();

				if($totalExistingPages > 0)
				{
					return $this->loadErrorPage(404, $slug);
				}
				else if($totalExistingPages == 0)
				{
					return $this->loadWelcomeCMSPage();
				}
			}
			
			// Get Twig view path
			$pageEntity = ( new RoutingPageViewPathAdapter($page) )->getPageEntity();
			$viewFile = $this->theme->getPageViewPath($pageEntity);

			return $this->twig->render($viewFile, $page);
		}

		/**
		 * Load error page by error number
		 *
		 * @param  Integer 	$errorNumber 	Error number
		 * @param  String 	$slug 			Slug
		 * @return String 	[type] 			View rendered as HTML
		 */
		public function loadErrorPage(int $errorNumber, string $slug) : string
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
		 * Load the CMS welcome page
		 *
		 * @return String 	[type]  HTML page view to render
		 */
		private function loadWelcomeCMSPage()
		{
			$viewFile = "@ReaccionCMSBundle/index.html.twig";
			$page 	  = [ 'cmsVersion' => ReaccionCMS::VERSION ];
			return $this->twig->render($viewFile, $page);
		}
	}
