<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Menu;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Cache\Adapter\ApcuAdapter;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
	use ReaccionEstudio\ReaccionCMSBundle\Helpers\CacheHelper;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeService;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Cache\CacheServiceInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuContentService;

	use Symfony\Component\HttpFoundation\RequestStack;

	/**
	 * Menu service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class MenuService
	{
		/**
		 * @var CacheServiceInterface
		 *
		 * Cache service
		 */
		private $cache;

		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManagerInterface
		 */
		private $em;

		/**
		 * @var MenuContentService
		 *
		 * MenuContent service
		 */
		private $menuContentService;

		/**
		 * @var Twig_Environment
		 *
		 * Twig
		 */
		private $twig;

		/**
		 * @var ThemeService
		 *
		 * Theme service
		 */
		private $theme;

		/**
		 * @var RequestStack
		 *
		 * RequestStack service
		 */
		private $request;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, MenuContentService $menuContentService, \Twig_Environment $twig, ThemeService $theme, CacheServiceInterface $cache, RequestStack $request)
		{
			$this->em = $em;
			$this->twig = $twig;
			$this->cache = $cache;
			$this->theme = $theme;
			$this->request = $request->getCurrentRequest();
			$this->menuContentService = $menuContentService;
		}

		/**
		 * Get menu HTML
		 *
		 * @param  string 		$slug			Menu slug
		 * @param  string 		$language		Menu language
		 * @return string 		[type] 			Menu HTML value
		 */
		public function getMenu(string $slug = 'navigation', string $language = "" ) : String
		{
			// get current app language
			$language = (strlen($language)) ? $language : $this->request->getLocale();

			// check if menu exists in cache storage
			$cacheKey  = $this->getCacheKey($slug, $language);
			$cacheItem = $this->cache->get($cacheKey);

			if($cacheItem !== null)
			{
				return $cacheItem;
			}
			else
			{
				// update menu html cache
				return $this->saveMenuHtmlInCache($slug, $language, $cacheKey);
			}
		}

		/**
		 * Update menu html value for cache
		 *
		 * @param  Menu 		$menu 			Menu entity
		 * @param  string 		$slug			Menu slug
		 * @param  string 		$language		Menu language
		 * @return string 		$menuHtml 		Menu HTML value
		 */
		public function saveMenuHtmlInCache(string $slug, string $language, string $cacheKey = "") : String
		{
			$cacheKey = ( ! strlen($cacheKey) ) ? $this->getCacheKey($slug, $language) : $cacheKey;

			// get menu from database
			$menu = $this->em->getRepository(Menu::class)->findOneBy(
						[
							'slug' => $slug,
							'language' => $language,
							'enabled' => true
						]
					);

			if(empty($menu)) return "";

			// get menu html
			$menuHtml = $this->buildMenuHtml($menu);

			// Save config value in cache
			$this->cache->set($cacheKey, $menuHtml);

			// return menu html
			return $menuHtml;
		}

		/**
		 * Build menu html
		 *
		 * @param  Menu 	$menu 		Menu entity
		 * @return string 	[type] 		Menu Html
		 */
		private function buildMenuHtml(Menu $menu) : String
		{
			// get menu items as nested array
			$nestedArray = $this->menuContentService->buildNestedArray($menu, true, true);

			// get current theme views path
			$currentThemePath = $this->theme->generateRelativeTwigViewPath("layout/menu.html.twig");

			// get menu html
			return $this->twig->render($currentThemePath, [ 'menu' => $nestedArray ]);
		}

		/**
		 * Get cache key for menu slug
		 *
		 * @param 	string 	$slug 		Menu slug
		 * @param  	string 	$lang 		Menu language
		 * @return  string 	[type] 		Menu cache key
		 */
		public function getCacheKey(string $slug, string $lang)
		{
			$suffix = "_" .  $lang . "_menu";
			return (new CacheHelper())->convertSlugToCacheKey($slug, $suffix);
		}

		/**
	     * Checks if given page has a relationship with any Menu
	     *
	     * @param  Integer 			$pageId 	Page Id
     	 * @return Menu|null		[type] 		Menu entity
	     */
	    public function getPageMenu(int $pageId)
	    {
	    	return $this->em->getRepository(MenuContent::class)->getPageMenu($pageId);
	    }
	}
