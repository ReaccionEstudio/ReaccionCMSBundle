<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Routing;

	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;

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
				// Get main route defined in database
				$page = $this->getMainPage();
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
		 * Load entry searching by slug
		 *
		 * @param  String 	$slug 			Route slug
		 * @return Array 	$entryResult 	Found entry entity
		 */
		public function loadEntry(String $slug="")
		{
			$entryResult = [];

			if(strlen($slug))
			{
				$entry = $this->searchEntryBySlug($slug);
			}

			if($entry !== null)
			{
				$templateView = $this->theme->getPageViewPath($entry);

				$entryResult = [
					'entry' => $entry, 
					'view'  => $templateView
				];
			}

			return $entryResult;
		}

		/**
		 * Load error page by error number
		 *
		 * @param Integer 	$errorNumber 	Error number
		 */
		public function loadErrorPage(Int $errorNumber) : String
		{
			$fullTemplatePath = $this->theme->getFullTemplatePath();
			
			// Get theme config file
			$themeConfigService = new ThemeConfigService($fullTemplatePath);
			$config = $themeConfigService->getConfig();

			if(isset($config['theme_config']['views'][$errorNumber . '_error']))
			{
				$baseFilename = $config['theme_config']['views'][$errorNumber . '_error'];
				return $this->theme->generateRelativeTwigViewPath($baseFilename);
			}

			return '';
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

		/**
		 * Search entry entity by slug
		 *
		 * @param  String 			$slug 	Route slug
		 * @return Entry | null 	[type] 	Found entry entity
		 */
		private function searchEntryBySlug(String $slug)
		{
			return $this->em->getRepository(Entry::class)->findOneBy(
				[
					'slug' => $slug,
					'enabled' => true
				]
			);
		}

		/**
		 * Get main page entity
		 *
		 * @return Page | null 	[type] 	Found Page entity
		 */
		private function getMainPage()
		{
			return $this->em->getRepository(Page::class)->findOneBy(
				[
					'mainPage' => true,
					'isEnabled' => true
				]
			);
		}
	}