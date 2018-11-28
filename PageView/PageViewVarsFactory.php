<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PageView;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewVars;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;
	
	class PageViewVarsFactory
	{
		/**
		 * Make PageViewVars object
		 *
		 * @param  Page 			$page 			Page entity
		 * @param  MenuService		$menuService 	Menu service
		 * @return PageViewVars 	[type] 			Page view vars object
		 */
		public static function makePageViewVars(Page $page, MenuService $menuService)
		{
			return new PageViewVars($page, $menuService);
		}
	}