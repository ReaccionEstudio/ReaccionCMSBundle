<?php 

	namespace App\ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Page;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewContentCollection;

	/**
	 * Page data transformer
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	final class PageDataTransformer
	{
		/**
		 * @var Page
		 * 
		 * Page entity
		 */
		private $page;

		/**
		 * Constructor
		 */
		public function __construct(Page $page)
		{
			$this->page = $page;
		}

		/**
		 * Generate array with the page entity view vars
		 *
		 * @param 	EntryService 	$entryService 	Entry service
		 * @return 	Array 			$pageViewVars 	View vars for current page entity
		 */
		public function getPageViewVars(EntryService $entryService) : Array
		{
			// create content collection
			$pageViewContentCollection = new PageViewContentCollection(
												$this->page->getContent(), 
												$entryService, 
												$this->page->getLanguage()
											);
			$contentCollection = $pageViewContentCollection->getContentCollection();

			// page vars
			$pageViewVars = [
				'id' => $this->page->getId(),
				'slug' => $this->page->getSlug(),
				'name' => $this->page->getName(),
				'type' => $this->page->getType(),
				'language' => $this->page->getLanguage(),
				'mainPage' => $this->page->isMainPage(),
				'templateView' => $this->page->getTemplateView(),
				'seo' => [
							'title' => $this->page->getSeoTitle(),
							'description' => $this->page->getSeoDescription(),
							'keywords' => $this->page->getSeoKeywords()
						 ],
				'content' => $contentCollection
			];

			return $pageViewVars;
		}
	}