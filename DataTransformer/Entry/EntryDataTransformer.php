<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Entry;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	use App\ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Entry\EntryToPageDataTransformer;

	/**
	 * Entry data transformer
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	final class EntryDataTransformer
	{
		/**
		 * @var Entry
		 *
		 * Entry entity
		 */
		private $entry;

		/**
		 * Constructor
		 */
		public function __construct(Entry $entry)
		{
			$this->entry = $entry;
		}

		/**
		 * Create a Page entity with the entry entity as page content
		 * 
		 * @param 	EntryService 	$entryService 		Entry service
		 * @return 	Page 			[type] 				Page entity
		 */
		public function getPageEntity(EntryService $entryService)
		{
			$entryToPageDataTransformer = new EntryToPageDataTransformer($this->entry, $entryService);
			return $entryToPageDataTransformer->getPage();
		}
	}