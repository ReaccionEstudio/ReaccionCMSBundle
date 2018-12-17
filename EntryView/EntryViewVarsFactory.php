<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\EntryView;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\EntryView\EntryViewVars;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	
	class EntryViewVarsFactory
	{
		/**
		 * Make EntryViewVars object
		 *
		 * @param  Entry 			$entry 			Entry entity
		 * @param  MenuService		$menuService 	Menu service
		 * @param  EntryService		$entryService 	Entries service
		 * @return EntryViewVars 	[type] 			Entry view vars object
		 */
		public static function makeEntryViewVars(Entry $entry, MenuService $menuService, EntryService $entryService)
		{
			return new EntryViewVars($entry, $menuService, $entryService);
		}
	}