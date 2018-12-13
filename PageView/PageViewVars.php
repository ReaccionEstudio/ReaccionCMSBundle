<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PageView;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewVarsInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;

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
		 * Constructor
		 *
		 * @var Page 	$page 	Page entity
		 */
		public function __construct(Page $page, MenuService $menuService)
		{
			$this->page = $page;
			$this->menuService = $menuService;
		}

		/**
		 * Get page view vars array
		 *
		 * @return Array 	$viewVars 	Page view vars
		 */
		public function getVars() : Array
		{
			$pageLanguage = $this->page->getLanguage();

			$viewVars = [
							'name' => $this->page->getName(),
							'seo' => [
								'title' => $this->page->getSeoTitle(),
								'description' => $this->page->getSeoDescription(),
								'keywords' => $this->page->getSeoKeywords()
							],
							'content' => $this->generateContentCollection(),
							'menus' => $this->menuService->getMenus($pageLanguage)
						];

			return $viewVars;
		}

		/**
		 * Generate content collection
		 * 
		 * @return Array  	$contentCollection 		Content collection
		 */
		private function generateContentCollection() : Array
		{
			$contentArray = $this->page->getContent();

			if(empty($contentArray)) return [];

			$contentCollection = [];

			foreach($contentArray as $content)
			{
				if(empty($content)) continue;

				$contentName = $content->getSlug();
				$contentValue = $content->getValue();

				$contentCollection[$contentName] = [
					'type' => $content->getType(),
					'value' => $contentValue
				];
			}

			return $contentCollection;
		}
	}