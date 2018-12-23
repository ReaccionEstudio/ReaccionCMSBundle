<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Entry;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;

	final class EntryToPageContentDataTransformer
	{
		/**
		 * @var Entry
		 *
		 * Entry entity
		 */
		private $entry;

		/**
		 * @var Array
		 *
		 * Entry data as page content array
		 */
		private $entryPageContent = [];

		/**
		 * Constructor
		 */
		public function __construct(Entry $entry)
		{
			$this->entry = $entry;
			$this->transform();
		}

		/**
		 * Get entryPageContent var value
		 *
		 * @return Array 	$this->entryPageContent 	Entry data as page content array
		 */
		public function getEntryPageContent() : Array
		{
			return $this->entryPageContent;
		}

		/**
		 * Transforms Entry entity data
		 *
		 * @return Void 	[type]
		 */
		private function transform() : void
		{
			$categoriesArray = [];
			$categories = $this->entry->getCategories();

			foreach($categories as $category)
			{
				$categoriesArray[] = [
					'id' => $category->getId(),
					'name' => $category->getName(),
					'slug' => $category->getSlug(),
					'language' => $category->getLanguage()
				];
			}

			$this->entryPageContent = [
				'id' => $this->entry->getId(),
				'name' => $this->entry->getName(),
				'slug' => $this->entry->getSlug(),
				'content' => $this->entry->getContent(),
				'resume' => $this->entry->getResume(),
				'tags' => $this->entry->getTags(),
				'language' => $this->entry->getLanguage(),
				'createdAt' => $this->entry->getCreatedAt(),
				'updatedAt' => $this->entry->getUpdatedAt(),
				'defaultImage' => $this->entry->getDefaultImage(),
				'categories' => $categoriesArray
			];
		}
	}