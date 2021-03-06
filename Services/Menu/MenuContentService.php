<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Menu;

	use Doctrine\ORM\EntityManagerInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

	/**
	 * Menu service
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class MenuContentService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManagerInterface
		 */
		private $em;

		/**
		 * @var LoggerServiceInterface
		 *
		 * Logger service
		 */
		private $logger;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, LoggerServiceInterface $logger)
		{
			$this->em 		= $em;
			$this->logger 	= $logger;
		}

		/**
		 * Build nested array with menu items
		 *
		 * @param  Menu 		$menu 					Menu entity
		 * @param  Boolean 		$hideDisabledItems 		Hide disabled menu items
		 * @param  Boolean 		$addPageSlugs 			Add page slugs
		 * @return Array  		$nested    				Menu nested array
		 */
		public function buildNestedArray(Menu $menu, bool $hideDisabledItems=true, bool $addPageSlugs=false) : Array
		{
			$nested 	= array();
			$source 	= array();
			$menuItems 	= $this->getMenuItemsAsArray($menu, $hideDisabledItems);

			if($addPageSlugs)
			{
				// get all pages
				$pageFilters = [ 'language' => $menu->getLanguage(), 'enabled' => true ];
				$pages 		 = $this->em->getRepository(Page::class)->getPages($pageFilters);

				// create new array to store page slugs
				$arrayPageSlugs = [];

				foreach($pages as $page)
				{
					$key = $page->getId();
					$arrayPageSlugs[$key] = $page->getSlug();
				}
			}

			// create source array
			foreach($menuItems as $menuItem)
			{
				// replace menu value when menu item type is page with the page slug
				if($addPageSlugs && $menuItem['type'] == "page")
				{
					$pageId = $menuItem['value'];

					if( ! isset($arrayPageSlugs[$pageId])) continue;

					$menuItem['value'] = $arrayPageSlugs[$pageId];
				}

				// add to source array
				$source[$menuItem['id']] = $menuItem;
			}

			// set items position keys
			$source = $this->setItemPositionKeys($source);

			// create nested array
			foreach ($source as &$s) 
			{
				if ( is_null($s['parent_id']) ) 
				{
					// no parent_id so we put it in the root of the array
					$nested[] = &$s;
				}
				else 
				{
					$pid = $s['parent_id'];
					
					if ( isset($source[$pid]) ) 
					{
						// If the parent ID exists in the source array
						// we add it to the 'children' array of the parent after initializing it.
						if ( ! isset($source[$pid]['children']) ) 
						{
							$source[$pid]['children'] = array();
						}

						$source[$pid]['children'][] = &$s;
					}
				}
			}

			return $nested;
		}

		/**
		 * Get all menu items as array
		 *
		 * @param  Boolean 		$hideDisabledItems 		Hide disabled menu items?
		 * @return Array 		[type] 					Array with all menu items
		 */
		public function getMenuItemsAsArray(Menu $menu, bool $hideDisabledItems=true) : Array
		{
			$dql =  "
					SELECT 
					m.id, 
					p.id AS parent_id, 
					m.name, 
					m.type, 
					m.target, 
					m.position,
					m.value,
					m.enabled 
					FROM  ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent m 
					LEFT JOIN ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent p 
					WITH p.id = m.parent 
					WHERE m.menu = :menuId
					";

			if($hideDisabledItems)
			{
				$dql .= " AND m.enabled = 1";
			}

			$dql .= " ORDER BY m.position ASC ";

			$query = $this->em->createQuery($dql)->setParameter("menuId", $menu->getId());
			return $query->getArrayResult();
		}

		/**
		 * Set item position keys (isLastPosition and isFirstPosition)
		 *
		 * @param  Array 	$source 	Source array
		 * @return Array 	$source 	Source result array
		 */
		private function setItemPositionKeys(Array $source) : Array
		{
			// 1) items without parents
			$lastPosition = 0;
			$lastPositionItemId = 0;
			$firstPosition = 999999;
			$firstPositionItemId = null;

			foreach($source as $key => $item)
			{
				if($item['parent_id'] != null) continue;
				
				if($lastPosition < $item['position'])
				{
					$lastPosition 		= $item['position'];
					$lastPositionItemId = $key;
				}
				
				if($firstPosition > $item['position'])
				{
					$firstPosition 		 = $item['position'];
					$firstPositionItemId = $key;
				}
			}

			// save into source array
			if($lastPositionItemId > 0)
			{
				$source[$lastPositionItemId]['isLastItem'] = true;
			}

			if($firstPositionItemId != null)
			{
				$source[$firstPositionItemId]['isFirstItem'] = true;
			}

			// 2) items with parents
			$itemsGroupedByParentIds = [];

			// group by parent ids
			foreach($source as $key => $item)
			{
				if($item['parent_id'] == null) continue;
				$parentId = $item['parent_id'];
				$item['source_id'] = $key;
				$itemsGroupedByParentIds[$parentId][] = $item;
			}

			foreach($itemsGroupedByParentIds as $key=> $itemsGroup)
			{
				$lastPosition = 0;
				$lastPositionItemId = 0;
				$firstPosition = 999999;
				$firstPositionItemId = null;
				$isUnique = false;

				foreach($itemsGroup as $subkey => $item)
				{
					if($lastPosition < $item['position'])
					{
						$lastPosition 		= $item['position'];
						$lastPositionItemId = $item['source_id'];
					}

					if($firstPosition > $item['position'])
					{
						$firstPosition 		 = $item['position'];
						$firstPositionItemId = $item['source_id'];
						
						if( ! isset($itemsGroupedByParentIds[$key][($subkey+1)]))
						{
							$isUnique = true;
						}
					}
				}

				// save into source array
				if($lastPositionItemId > 0)
				{
					$source[$lastPositionItemId]['isLastItem'] = true;
				}

				if($firstPositionItemId != null)
				{
					if($isUnique)
					{
						$source[$firstPositionItemId]['isUnique'] = true;
					}
					else
					{
						$source[$firstPositionItemId]['isFirstItem'] = true;
					}
				}
			}

			return $source;
		}
	}
