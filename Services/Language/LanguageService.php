<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Language;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Language\LanguageCookie;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Language\LanguageFacade;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Authentication\AuthenticationService;

	/**
	 * Language service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class LanguageService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManagerInterface
		 */
		private $em;

		/**
		 * @var User
		 *
		 * User entity
		 */
		private $user = null;

		/**
		 * @var ConfigServiceInterface
		 *
		 * Config service
		 */
		private $config;

		/**
		 * @var Session
		 *
		 * Session
		 */
		private $session;

		/**
		 * @var String
		 *
		 * Default language
		 */
		private $language;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, SessionInterface $session, ConfigServiceInterface $config, AuthenticationService $authenticationService, String $defaultLanguage = 'en')
		{
			$this->em 					 = $em;
			$this->session 				 = $session;
			$this->config 				 = $config;
			$this->language 			 = $defaultLanguage;
			$this->authenticationService = $authenticationService;
		}

		/**
		 * Get user language
		 *
		 * @return String 	$this->language 	User language
		 */
		public function getLanguage() : String
		{
			$this->setCurrentUserTokenLanguage();

			$language = ( new LanguageFacade($this->user, $this->config) )->getLanguage();

			if($language && in_array($language, Languages::LANGUAGES))
			{
				$this->language = $language;
			}

			return $this->language;
		}

		/**
		 * Update user language
		 *
		 * @return Boolean 	true|false  Update result
		 */
		public function updateLanguage(String $language) : Bool
		{
			// Check if language var is valid
			if( $language == "" || ! in_array($language, Languages::LANGUAGES))
			{
				return false;
			}

			$this->setCurrentUserTokenLanguage();

			// If user is signed in, update language value in the user preferences
			if( ! empty($this->user))
			{
				// update user entity
				return $this->updateUserEntityLanguage($this->user->getId(), $language);
			}

			// update language
			return (new LanguageCookie($this->config))->setLanguageCookieValue($language);
		}

		/**
		 * Update user entity language value
		 *
		 * @return Boolean 	true|false  Update result
		 */
		public function updateUserEntityLanguage($user, $language) : Bool
		{
			$userId = ($user instanceof User) ? $user->getId() : $user;

			try
			{
				$userEntity = $this->em->getRepository(User::class)->findOneBy(['id' => $userId]);
				$userEntity->setLanguage($language);

				// update
				$this->em->persist($userEntity);
				$this->em->flush();

				// update current user session language value
				$this->authenticationService->createUserToken($userEntity);

				return true;
			}
			catch(\Exception $e)
			{
				return false;
			}
		}

		/**
		 * Set current user language from user token
		 */
		private function setCurrentUserTokenLanguage() : void
		{
			$userLanguage 		 = null;
			$serializedUserToken = $this->session->get("_security_main");
			$unserializedToken   = unserialize($serializedUserToken);

			if($unserializedToken)
			{
				$this->user = $unserializedToken->getUser();
				$tokenAttrs = $unserializedToken->getAttributes();
				
				if(isset($tokenAttrs['userLanguage']))
				{
					$userLanguage = $tokenAttrs['userLanguage'];
				}
			}

			if($this->user != null)
			{
				$this->user->setLanguage($userLanguage);
			}
		}

	    /**
		 * Set user entity
		 *
		 * @param  User 	$user 	User entity
		 * @return self
		 */
		public function setUser(User $user) : self
		{
			$this->user = $user;
			return $this;
		}
	}