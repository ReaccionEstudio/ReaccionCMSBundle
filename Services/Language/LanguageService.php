<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Language;

	use Symfony\Component\HttpFoundation\Session\Session;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Language\LanguageFacade;

	/**
	 * Language service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class LanguageService
	{
		/**
		 * @var User
		 *
		 * User entity
		 */
		private $user = null;

		/**
		 * @var ConfigService
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
		public function __construct(Session $session, ConfigService $config, String $defaultLanguage)
		{
			$this->session 	= $session;
			$this->config 	= $config;
			$this->language = $defaultLanguage;
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

			$this->user->setLanguage($userLanguage);
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