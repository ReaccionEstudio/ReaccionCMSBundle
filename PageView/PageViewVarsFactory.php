<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PageView;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewVars;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	
	class PageViewVarsFactory
	{
		/**
		 * Make PageViewVars object
		 *
		 * @param  Page 			$page 			Page entity
		 * @param  MenuService		$menuService 	Menu service
		 * @param  EntryService		$entryService 	Entries service
		 * @return PageViewVars 	[type] 			Page view vars object
		 */
		public static function makePageViewVars(Page $page, MenuService $menuService, EntryService $entryService)
		{
			return new PageViewVars($page, $menuService, $entryService);
		}
	}