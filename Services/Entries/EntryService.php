<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries;

	use Knp\Component\Pager\Paginator;
	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;

	/**
	 * Entry service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class EntryService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var Paginator
		 *
		 * KNP PaginatorBundle service
		 */
		private $paginator;

		/**
		 * @var ConfigServiceInterface
		 *
		 * Config service
		 */
		private $config;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Paginator $paginator, ConfigServiceInterface $config)
		{
			$this->em 		 = $em;
			$this->paginator = $paginator;
			$this->config 	 = $config;
		}

		/**
		 * Get entries for current page language
		 *
		 * @param  String 				$language 	Page language
		 * @param  Integer 				$page 		Pagination page number
		 * @return SlidingPagination 	$entries 	All entries
		 */
		public function getEntries(String $language = "en", Int $page = 1) : SlidingPagination
		{
			// get entries
			$entries = $this->em->getRepository(Entry::class)->getEntries();

			// load pagination limit parameter from config
			$limit = 	($this->config->get("entries_list_pagination_limit") > 0) 
						? $this->config->get("entries_list_pagination_limit")
						: 10;

			// pagination
			$entries = $this->paginator->paginate(
		        $entries,
		        $page,
		        $limit
		    );

			return $entries;
		}

		/**
		 * Get categories for current page language
		 *
		 * @param  String 	$language 			Page language
		 * @param  Array 				$filters 	Custom filters
		 * @return Array 	$entryCategories 	Entry categories list
		 */
		// TODO: Add categories to cache storage
		public function getCategories(String $language = "en", Array $filters = []) : Array
		{
			$entryCategories = $this->em->getRepository(EntryCategory::class)->findBy(
									[
										'language' => $language,
										'enabled' => true
									],
									[ 'name' => 'ASC' ]
								);

			return $entryCategories;
		}

		/**
		 * Get previous and next entries for a given entry
		 *
		 * @param  Entry 	$entry 			Entry entity
		 * @param  String 	$language 		Entry language
		 * @return Array 	$arrayResult 	Query results
		 */
		public function getPreviousAndNextEntries(Entry $entry, String $language = "en")
		{
			$arrayResult = [
				'next' => [],
				'previous' => []
			];

			$result = $this->em->getRepository(Entry::class)->getPreviousAndNextEntries($entry, $language);

			if($result)
			{
				$entryId = $entry->getId();

				foreach($result as $entryRow)
				{
					if($entryId > $entryRow['id'])
					{
						$arrayResult['next'] = $entryRow;
					}
					else if($entryId < $entryRow['id'])
					{
						$arrayResult['previous'] = $entryRow;
					}
				}
			}

			return $arrayResult;
		}
	}