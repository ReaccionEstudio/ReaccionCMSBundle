<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Entry;

	use Doctrine\Common\Collections\ArrayCollection;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;

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
			// create content entity
			$pageContent = new PageContent();
			$pageContent->setValue($this->entry);
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

			$this->page->setName($entryName);
			$this->page->setType("entry");
			$this->page->setSlug($this->entry->getSlug());
			$this->page->setSeoTitle($entryName);
			$this->page->setSeoDescription($entryResume);
			$this->page->setSeoKeywords("");

			$pageContentCollection = $this->generatePageContent();

			if($pageContentCollection)
			{
				$this->page->setContent($pageContentCollection);
			}
		}
	}