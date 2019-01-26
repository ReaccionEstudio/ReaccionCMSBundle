<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Entry;

	use Doctrine\Common\Collections\ArrayCollection;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	use ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Entry\EntryToPageContentDataTransformer;

	/**
	 * Transforms an entry entity into a Page entity with the entry as page content
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	final class EntryToPageDataTransformer
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
		 * @var EntryService
		 *
		 * Entry service
		 */
		private $entryService;

		/**
		 * Constructor
		 */
		public function __construct(Entry $entry, EntryService $entryService)
		{
			$this->page 		= new Page();
			$this->entry 		= $entry;
			$this->entryService = $entryService;

			$this->setPageEntityValues();
		}

		/**
		 * Get page entity
		 *
		 * @return Page 	$this->page 	Page entity
		 */
		public function getPage() : Page
		{
			return $this->page;
		}

		/**
		 * Generate PageContent entities array 
		 *
		 * @return Array 	$pageContentCollection 	Page content array collection
		 */
		private function generatePageContent() : ArrayCollection
		{
			// get entry array data
			$entryArrayData = ( new EntryToPageContentDataTransformer($this->entry) )->getEntryPageContent();

			// create content entity
			$pageContent = new PageContent();
			$pageContent->setValue($entryArrayData);
			$pageContent->setSlug($this->entry->getSlug());
			$pageContent->setType("entry");

			// create array collection
			$pageContentCollection = new ArrayCollection();
			$pageContentCollection->add($pageContent);

			return $pageContentCollection;
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
			$entryLanguage = $this->entry->getLanguage();
			$nextAndPreviousEntries = $this->entryService->getPreviousAndNextEntries($this->entry, $entryLanguage);
			
			$options = [
				'entry_id' => $this->entry->getId(),
				'totalComments' => $this->entry->getTotalComments(),
				'nextEntry' => $nextAndPreviousEntries['next'],
				'previousEntry' => $nextAndPreviousEntries['previous']
			];
			$options = serialize($options);

			$this->page->setName($entryName);
			$this->page->setType("entry");
			$this->page->setSlug($this->entry->getSlug());
			$this->page->setSeoTitle($entryName);
			$this->page->setSeoDescription($entryResume);
			$this->page->setSeoKeywords("");
			$this->page->setOptions($options);

			$pageContentCollection = $this->generatePageContent();

			if($pageContentCollection)
			{
				$this->page->setContent($pageContentCollection);
			}
		}
	}