<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Cache\Adapter\ApcuAdapter;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService AS AdminMenuService;
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
		 * Menu cache key
		 */
		private $cacheKey;

		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var MenuService as AdminMenuService
		 *
		 * EntityManager
		 */
		private $adminMenuService;

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
		public function __construct(EntityManagerInterface $em, AdminMenuService $adminMenuService, $twig, ThemeService $theme)
		{
			$this->em = $em;
			$this->adminMenuService = $adminMenuService;
			$this->twig = $twig;
			$this->theme = $theme;

			// cache
			$this->cache = new ApcuAdapter();
			$this->cacheKey = "navigation.menu";
		}

		/**
		 * Get menu HTML
		 *
		 * @return String 	$menuHtml 	Menu HTML
		 */
		public function getMenuHtml() : String
		{
			$menuHtml   = "";
			$cachedItem = $this->cache->getItem($this->cacheKey);

			if($cachedItem->isHit())
			{
				// key is cached
				$menuHtml = $cachedItem->get();
			}
			else
			{
				// get menu html
				$menuHtml = $this->buildMenuHtml();

				// Save config value in cache
				$cachedItem->set($menuHtml);
				$this->cache->save($cachedItem);
			}

			return $menuHtml;
		}

		/**
		 * Build menu html
		 *
		 * @return String 	[type] 		Menu Html
		 */
		private function buildMenuHtml() : String
		{
			// get menu items as nested array
			$nestedArray = $this->adminMenuService->buildNestedArray(true);

			// get current theme views path
			$currentThemePath = $this->theme->generateRelativeTwigViewPath("layout/menu.html.twig");

			// get menu html
			return $this->twig->render($currentThemePath, [ 'menu' => $nestedArray ]);
		}
	}