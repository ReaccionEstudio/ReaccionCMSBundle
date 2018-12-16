<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries;

	use Knp\Component\Pager\Paginator;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;

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
		 * @var RequestStack
		 *
		 * RequestStack service
		 */
		private $request;

		/**
		 * @var ConfigService
		 *
		 * Config service
		 */
		private $config;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Paginator $paginator, RequestStack $request, ConfigService $config)
		{
			$this->em 		 = $em;
			$this->request 	 = $request;
			$this->paginator = $paginator;
			$this->config 	 = $config;
		}

		/**
		 * Get entries for current page language
		 *
		 * @param  String 				$language 	Page language
		 * @return SlidingPagination 	$entries 	All entries
		 */
		public function getEntries(String $language = "en") : SlidingPagination
		{
			// get entries
			$entries = $this->em->getRepository(Entry::class)->findBy(
							[
								'language' => $language,
								'enabled' => true
							],
							['id' => 'DESC']
						);

			// load pagination limit parameter from config
			$limit = 	($this->config->get("entries_list_pagination_limit") > 0) 
						? $this->config->get("entries_list_pagination_limit")
						: 10;

			// get current page
			$currentPage =  ($this->request->getCurrentRequest()->get('page'))
							? $this->request->getCurrentRequest()->get('page')
							: 1;

			// pagination
			$entries = $this->paginator->paginate(
		        $entries,
		        $currentPage,
		        $limit
		    );

			return $entries;
		}

		/**
		 * Get categories for current page language
		 *
		 * @param  String 	$language 			Page language
		 * @return Array 	$entryCategories 	Entry categories list
		 */
		public function getCategories(String $language = "en") : Array
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
	}