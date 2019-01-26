<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Language;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\PageTranslationGroup;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

	/**
	 * Helper class for managing page redirections which have a page translation group relationship.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class LanguagePageTranslationGroupRedirection
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManagerInterface
		 */
		private $em;

		/**
	     * @var UrlGeneratorInterface
	     */
	    protected $router;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $router)
		{
			$this->em = $em;
			$this->router = $router;
		}

		/**
		 * Redirect by page translation group for the given language
		 */
		public function redirectByTranslationGroup(String $language, String $refererUrl, String $route, String $routeSlug="")
		{
			if($route == "index_slug")
			{
				// find page entity by slug
				$redirectionUrl = $this->findPageEntityBySlug($language, $routeSlug);
			}
			else if($route == "index")
			{
				// get main page entity for given language
				$mainPageEntity = $this->getMainTranslationPageEntity($language);
				$redirectionUrl = $this->router->generate('index_slug', [ 'slug' => $mainPageEntity->getSlug() ]);
			}
			else
			{
				// redirect to the referer url
				$redirectionUrl = $refererUrl;
			}
			
			return new RedirectResponse($redirectionUrl);
		}

		/**
		 * Find page entity by slug for given language
		 *
		 * @param  String 	$language 	Page language
		 * @param  String 	$routeSlug	Referer route slug
		 * @return String 	[type]		Translation page url
		 */
		private function findPageEntityBySlug(String $language, String $routeSlug) : String
		{
			$currentPageEntity = $this->em->getRepository(Page::class)->findOneBy(['slug' => $routeSlug ]);
			$translationGroup  = $currentPageEntity->getTranslationGroup();

			$pageFilters = [ 'translationGroup' => $translationGroup, 'language' => $language ];
			$pageEntity  = $this->em->getRepository(Page::class)->findOneBy($pageFilters);

			if( empty($pageEntity) )
			{
				// redirect to language main page
				$pageEntity = $this->getMainTranslationPageEntity($language);
			}

			return $this->router->generate('index_slug', [ 'slug' => $pageEntity->getSlug() ]);
		}

		/**
		 * Get the main page for given translation
		 *
		 * @param  String 		$language 	Page language
		 * @return Page|null 	[type]		Main page entity
		 */
		private function getMainTranslationPageEntity(String $language)
		{
			$filters = [ 'language' => $language, 'mainPage' => true ];
			return $this->em->getRepository(Page::class)->findOneBy($filters);
		}
	}