<?php
	
	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Routing;

	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;

	/**
	 * Adapts the page cache data into a new Page entity
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class RoutingPageViewPathAdapter
	{
		/**
		 * @var Array
		 *
		 * Page cache data array
		 */
		private $page;

		/**
		 * @var Page
		 *
		 * Page entity
		 */
		private $pageEntity;

		/**
		 * Constructor
		 */
		public function __construct(Array $page)
		{
			$this->page = $page;
			$this->convertToEntity();
		}

		/**
		 * Converts data array to entity
		 */
		public function convertToEntity() : void
		{
			$this->pageEntity = new Page();
			$this->pageEntity->setTemplateView($this->page['templateView']);
			$this->pageEntity->setType($this->page['type']);
		}

		/**
		 * Get pageEntity var value
		 *
		 * @return Page 	$this->pageEntity 	Page entity
		 */
		public function getPageEntity() : Page
		{
			return $this->pageEntity;
		}
	}