<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries;

	use Knp\Component\Pager\Paginator;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
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
		 * Get entries for current page lang
		 */
		public function getEntries(String $language = "en", Int $page = 1) : SlidingPagination
		{
			// get entries
			$entries = $this->em->getRepository(Entry::class)->findBy(
							['language' => $language],
							['id' => 'DESC']
						);

			// load pagination limit parameter from config
			$limit = 	($this->config->get("entries_list_pagination_limit") > 0) 
						? $this->config->get("entries_list_pagination_limit")
						: 10;

			// pagination
			$entries = $this->paginator->paginate(
		        $entries,
		        $this->request->getCurrentRequest()->get('page'),
		        $limit
		    );

			return $entries;
		}
	}