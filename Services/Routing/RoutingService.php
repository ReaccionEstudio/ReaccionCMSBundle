<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Routing;

	use Doctrine\ORM\EntityManagerInterface;
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
		 * @var CacheService
		 *
		 * Cache service
		 */
		private $cacheService;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, ThemeService $theme, PageCacheService $pageCache, \Twig_Environment $twig, CacheService $cacheService)
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

			// TODO: Create Facade class

			// Load page by slug or main page if slug is empty
			$page = (strlen($slug)) 
					? $this->pageCache->getPage($slug)
					: $this->pageCache->getMainPage();
			

			// if page was not found, we check if any entry matches with this slug
			if( ! $page)
			{
				$page = $this->pageCache->getEntryDetailPage($slug);
			}
			
			// if page was found gets the twig view path
			if($page)
			{	
				$pageEntity = new Page();
				$pageEntity->setTemplateView($page['templateView']);
				$pageEntity->setType($page['type']);
				$viewFile = $this->theme->getPageViewPath($pageEntity);	
			}
			else
			{
				// if page was not found, shows 404 error page
				return $this->loadErrorPage(404, $slug);
			}

			// If there isn't a twig view file path, we load the default ReaccionCMS home page
			if( ! strlen($viewFile) )
			{
				$viewFile = "@ReaccionCMSBundle/index.html.twig";
				$page 	  = ['cmsVersion' => 0.1];
			}

			return $this->twig->render($viewFile, $page);
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
	}