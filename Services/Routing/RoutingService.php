<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Routing;

	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeService;

	/**
	 * ReaccionCMSBundle routing service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class RoutingService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var ThemeService
		 *
		 * Theme service
		 */
		private $theme;

		/**
		 * Constructor
		 */
		public function __construct(EntityManager $em, ThemeService $theme)
		{
			$this->em = $em;
			$this->theme = $theme;
		}

		/**
		 * Load page searching by slug
		 *
		 * @param  String 		$slug 	Route slug
		 * @return Page | null 	[type] 	Found Page entity
		 */
		public function loadPage(String $slug="")
		{
			$page = null;

			if( ! strlen($slug))
			{
				// TODO: Get main route defined in database
			}
			else
			{
				// Load page for requested slug
				$page = $this->searchPageBySlug($slug);
			}

			if($page !== null)
			{
				$templateView = $this->theme->getPageViewPath($page);
				$page->setTemplateView($templateView);
			}

			return $page;
		}

		/**
		 * Load error page by error number
		 *
		 * @param Integer 	$errorNumber 	Error number
		 */
		public function loadErrorPage(Int $errorNumber)
		{
			
		}

		/**
		 * Search page entity by slug
		 *
		 * @param  String 		$slug 	Route slug
		 * @return Page | null 	[type] 	Found Page entity
		 */
		private function searchPageBySlug(String $slug)
		{
			return $this->em->getRepository(Page::class)->findOneBy(
				[
					'slug' => $slug,
					'isEnabled' => true
				]
			);
		}
	}