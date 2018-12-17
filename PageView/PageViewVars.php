<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PageView;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewVarsInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewContentCollection;

	class PageViewVars implements PageViewVarsInterface
	{
		/**
		 * @var Page
		 *
		 * Page entity
		 */
		private $page;

		/**
		 * @var MenuService
		 *
		 * Menu service
		 */
		private $menuService;

		/**
		 * @var EntryService
		 *
		 * Entry service
		 */
		private $entryService;

		/**
		 * Constructor
		 *
		 * @var Page 	$page 	Page entity
		 */
		public function __construct(Page $page, MenuService $menuService, EntryService $entryService)
		{
			$this->page 		= $page;
			$this->menuService  = $menuService;
			$this->entryService = $entryService;
		}

		/**
		 * Get page view vars array
		 *
		 * @return Array 	$viewVars 	Page view vars
		 */
		public function getVars() : Array
		{
			$pageLanguage = $this->page->getLanguage();
			$pageContent  = $this->page->getContent();

			// create content collection
			$pageViewContentCollection = new PageViewContentCollection($pageContent, $this->entryService, $pageLanguage);

			$viewVars = [
							'name' => $this->page->getName(),
							'seo' => [
								'title' => $this->page->getSeoTitle(),
								'description' => $this->page->getSeoDescription(),
								'keywords' => $this->page->getSeoKeywords()
							],
							'content' => $pageViewContentCollection->getContentCollection(),
							'menus' => $this->menuService->getMenus($pageLanguage)
						];

			return $viewVars;
		}
	}