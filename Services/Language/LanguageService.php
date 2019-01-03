<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Language;

	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Language\LanguageFacade;
	use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

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
		private $user;

		/**
		 * @var ConfigService
		 *
		 * Config service
		 */
		private $config;

		/**
		 * @var TokenStorage
		 *
		 * Token storage service
		 */
		private $token_storage;

		/**
		 * @var String
		 *
		 * Default language
		 */
		private $language;

		/**
		 * Constructor
		 */
		public function __construct(TokenStorage $token_storage, ConfigService $config, String $defaultLanguage)
		{
			$this->token_storage = $token_storage;
			$this->config 		 = $config;
			$this->user 		 = $this->getUser();
			$this->language 	 = $defaultLanguage;
		}

		/**
		 * Get user language
		 *
		 * @return String 	$this->language 	User language
		 */
		public function getLanguage() : String
		{
			$language = ( new LanguageFacade($this->user, $this->config) )->getLanguage();

			if($language && in_array($language, Languages::LANGUAGES))
			{
				$this->language = $language;
			}

			return $this->language;
		}

		/**
	     * Get a user from the Security Token Storage.
	     *
	     * @return mixed
	     * @see TokenInterface::getUser()
	     */
	    private function getUser()
	    {
	        if (null === $token = $this->token_storage->getToken()) 
	        {
	            return;
	        }

	        if ( ! is_object($user = $token->getUser()))
	        {
	            return;
	        }

	        return $user;
	    }
	}