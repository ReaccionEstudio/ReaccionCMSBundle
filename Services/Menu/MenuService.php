<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Cache\Adapter\ApcuAdapter;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuContentService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeService;

	/**
	 * Menu service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class MenuService
	{
		/**
		 * @var ApcuAdapter
		 *
		 * APCu adapter
		 */
		private $cache;

		/**
		 * @var String
		 *
		 * Menu cache key prefix
		 */
		private $cacheKeyPrefix;

		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var MenuContentService
		 *
		 * EntityManager
		 */
		private $menuContentService;

		/**
		 * @var Twig
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
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, MenuContentService $menuContentService, $twig, ThemeService $theme)
		{
			$this->em = $em;
			$this->twig = $twig;
			$this->theme = $theme;
			$this->menuContentService = $menuContentService;

			// cache
			$this->cache = new ApcuAdapter();
			$this->cacheKeyPrefix = "menu";
		}

		/**
		 * Get all available menus for specified page language
		 *
		 * @param   String 	$currentLang 	Page language
		 * @return  Array 	$menusHtml 		Menus HTML
		 */
		public function getMenus(String $language="en") : Array
		{
			$menusHtml = [];

			// get all menus
			$menus = $this->em->getRepository(Menu::class)->findBy(
				[
					'enabled' => true,
					'language' => $language
				]
			);

			// get menus html
			foreach($menus as $menu)
			{
				$menuKey = $menu->getSlug();
				$menusHtml[$menuKey] = $this->getMenuHtml($menu);
			}

			return $menusHtml;
		}

		/**
		 * Get menu HTML
		 *
		 * @param  Menu 	$menu 			Menu entity
		 * @return String 	$menuHtml 		Menu HTML
		 */
		private function getMenuHtml(Menu $menu) : String
		{
			$menuHtml  = "";
			$cacheKey  = $this->cacheKeyPrefix . $menu->getSlug();
			$cacheItem = $this->cache->getItem($cacheKey);

			if($cacheItem->isHit())
			{
				// key is cached
				$menuHtml = $cacheItem->get();
			}
			else
			{
				// update menu html cache
				$menuHtml = $this->updateMenuHtmlCache($menu, $cacheItem);
			}

			return $menuHtml;
		}

		/**
		 * Update menu html value for cache
		 *
		 * @param  Menu 		$menu 			Menu entity
		 * @param  CacheItem 	$cacheItem 		Cache item object
		 * @return String 		$menuHtml 		Menu HTML value
		 */
		public function updateMenuHtmlCache(Menu $menu, $cacheItem=null) : String
		{
			if($cacheItem == null)
			{
				$cacheKey  = $this->cacheKeyPrefix . $menu->getSlug();
				$cacheItem = $this->cache->getItem($cacheKey);
			}

			// get menu html
			$menuHtml = $this->buildMenuHtml($menu);

			// Save config value in cache
			$cacheItem->set($menuHtml);
			$this->cache->save($cacheItem);

			return $menuHtml;
		}

		/**
		 * Build menu html
		 *
		 * @param  Menu 	$menu 		Menu entity
		 * @return String 	[type] 		Menu Html
		 */
		private function buildMenuHtml(Menu $menu) : String
		{
			// get menu items as nested array
			$nestedArray = $this->menuContentService->buildNestedArray($menu, true);

			// get current theme views path
			$currentThemePath = $this->theme->generateRelativeTwigViewPath("layout/menu.html.twig");

			// get menu html
			return $this->twig->render($currentThemePath, [ 'menu' => $nestedArray ]);
		}
	}