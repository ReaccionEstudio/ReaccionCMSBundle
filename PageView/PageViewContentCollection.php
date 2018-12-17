<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PageView;
	
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;

	final class PageViewContentCollection
	{
		/**
		 * @var Array
		 *
		 * Array with content for the new page entity
		 */
		private $content = [];

		/**
		 * @var PersistentCollection
		 *
		 * Content collection
		 */
		private $contentCollection = [];

		/**
		 * @var String
		 *
		 * Current language
		 */
		private $language;

		/**
		 * @var EntryService
		 *
		 * Entry service
		 */
		private $entryService;

		/**
		 * Constructor
		 */
		public function __construct($content, EntryService $entryService, String $language = "en")
		{
			$this->content 		= $content;
			$this->language 	= $language;
			$this->entryService = $entryService;

			$this->generateContentCollection();
		}

		/**
		 * Generate content collection
		 * 
		 * @return PageViewContentCollection  	$this	Content collection
		 */
		private function generateContentCollection() : PageViewContentCollection
		{
			if(empty($this->content)) return [];

			$this->contentCollection  = [];

			foreach($this->content as $content)
			{
				if(empty($content)) continue;

				$contentName 	= $content->getSlug();
				$contentValue 	= $content->getValue();
				$contentType 	= $content->getType();
				$contentKey 	= $this->generateContentCollectionKey($content);

				// check content type
				if($contentType == "entries_list")
				{
					$this->contentCollection[$contentKey] = $this->entryService->getEntries($this->language);
				}
				else if($contentType == "entry_categories")
				{
					$this->contentCollection[$contentKey] = $this->entryService->getCategories($this->language);
				}
				else
				{
					$this->contentCollection[$contentKey] = [
						'type' => $contentType,
						'value' => $contentValue
					];
				}
			}

			return $this;
		}

		/**
		 * Generate collection array key for specified content
		 *
		 * @param  PageContent 	$content 					PageContent value
		 * @return String 		$contentName|$contentType 	Array key value
		 */
		private function generateContentCollectionKey(PageContent $content) : String
		{
			$typesAsKeys  = ['entry'];
			$contentName  = $content->getSlug();
			$contentValue = $content->getValue();
			$contentType  = $content->getType();

			if( in_array($contentType, $typesAsKeys) ) return $contentType;

			$contentName = str_replace("-", "_", $contentName);

			return $contentName;
		}

		/**
		 * Get content collection array
		 *
		 * @return Array 	$this->contentCollection 	Content collection array
		 */
		public function getContentCollection() : Array
		{
			return $this->contentCollection;
		}
	}