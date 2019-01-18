<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Language;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageTranslationGroup;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

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
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em)
		{
			$this->em = $em;
		}

		/**
		 * Redirect by page translation group for the given language
		 */
		public function redirectByTranslationGroup(String $language, String $route, String $routeSlug="")
		{
			// TODO ...
			var_dump($route);
			var_dump($routeSlug);
		}
	}