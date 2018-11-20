<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PageView;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewVars;
	
	class PageViewVarsFactory
	{
		/**
		 * Make PageViewVars object
		 *
		 * @param  Page 			$page 	Page entity
		 * @return PageViewVars 	[type] 	Page view vars object
		 */
		public static function makePageViewVars(Page $page)
		{
			return new PageViewVars($page);
		}
	}