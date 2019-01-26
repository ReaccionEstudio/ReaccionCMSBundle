<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Routing;

	use ReaccionEstudio\ReaccionCMSAdminBundle\Services\Cache\PageCacheService;

	/**
	 * Get page cache data required for the routing service
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class RoutingPageCacheData
	{
		/** 
		 * @var Array
		 *
		 * Page cache data
		 */
		private $pageCacheData = [];

		/**
		 * Constructor
		 */
		public function __construct(String $slug = "", PageCacheService $pageCache)
		{
			$this->slug 	 = $slug;
			$this->pageCache = $pageCache;

			$this->load();
		}

		/**
		 * Return $pageCacheData value
		 *
		 * @return Array 	$this->pageCacheData  	Page cache data
		 */
		public function getPageCacheData() : Array
		{
			return $this->pageCacheData;
		}

		/**
		 * Load page cache data by slug
		 */
		private function load() : void
		{
			// Load page by slug or main page if slug is empty
			if(strlen($this->slug)) 
			{
				$this->pageCacheData = $this->pageCache->getPage($this->slug);
			}
			else
			{
				$this->pageCacheData = $this->pageCache->getMainPage();
			}

			// if page was not found, we check if any entry matches with this slug
			if( ! $this->pageCacheData)
			{
				$this->pageCacheData = $this->pageCache->getEntryDetailPage($this->slug);
			}
		}
	}