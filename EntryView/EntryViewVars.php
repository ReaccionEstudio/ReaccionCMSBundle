<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\EntryView;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSBundle\EntryView\EntryViewVarsInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewContentCollection;

	class EntryViewVars  implements EntryViewVarsInterface
	{
		/**
		 * @var Page
		 *
		 * Page entity
		 */
		private $page;

		/**
		 * @var Entry
		 *
		 * Entry entity
		 */
		private $entry;

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
		 */
		public function __construct(Entry $entry, MenuService $menuService, EntryService $entryService)
		{
			$this->entry 		= $entry;
			$this->menuService  = $menuService;
			$this->entryService = $entryService;
			$this->setPageEntityValues();
		}

		/**
		 * Get page view vars array
		 *
		 * @return Array 	$viewVars 	Page view vars
		 */
		public function getVars() : Array
		{
			$pageLanguage = $this->entry->getLanguage();

			// generate content
			$pageContent = $this->generatePageContent();
			$contentCollection = new PageViewContentCollection($pageContent, $this->entryService, $pageLanguage);

			// create view vars
			$viewVars = [
							'name' => $this->page->getName(),
							'seo' => [
								'title' => $this->page->getSeoTitle(),
								'description' => $this->page->getSeoDescription(),
								'keywords' => $this->page->getSeoKeywords()
							],
							'content' => $contentCollection->getContentCollection(),
							'menus' => $this->menuService->getMenus($pageLanguage)
						];

			return $viewVars;
		}

		/**
		 * Generate PageContent entities array 
		 *
		 * @return Array 	[type] 	PageContent entities array
		 */
		private function generatePageContent() : Array
		{
			$pageContent = new PageContent();
			$pageContent->setValue($this->entry);
			$pageContent->setSlug($this->entry->getSlug());
			$pageContent->setType("entry");

			return [ $pageContent ];
		}

		/**
		 * Set entry values into page entity
		 *
		 * @return void 	[type]
		 */
		private function setPageEntityValues() : void
		{
			$entryName = $this->entry->getName();
			$entryResume = $this->entry->getResume();

			$this->page = new Page();
			$this->page->setName($entryName);
			$this->page->setSeoTitle($entryName);
			$this->page->setSeoDescription($entryResume);
			$this->page->setSeoKeywords("");
		}
	}