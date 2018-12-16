<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PageView;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewVarsInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;

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

			$contentCollection  = [];

			foreach($contentArray as $content)
			{
				if(empty($content)) continue;

				$contentName 	= $content->getSlug();
				$contentValue 	= $content->getValue();
				$contentType 	= $content->getType();

				// check content type
				if($contentType == "entries_list")
				{
					// add content to array collection
					$contentCollection[$contentType] = $this->entryService->getEntries($this->page->getLanguage());
				}
				else if($contentType == "entry_categories")
				{
					$contentCollection[$contentType] = $this->entryService->getCategories($this->page->getLanguage());
				}
				else
				{
					// add content to array collection
					$contentCollection[$contentName] = [
						'type' => $contentType,
						'value' => $contentValue
					];
				}
			}

			return $contentCollection;
		}
	}