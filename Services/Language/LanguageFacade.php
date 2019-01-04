<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Language;

	use Cocur\Slugify\Slugify;

	/**
	 * Language facade class.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class LanguageFacade
	{
		/**
		 * @var User
		 *
		 * User entity
		 */
		private $user;

		/**
		 * @var String
		 *
		 * Language cookie name
		 */
		private $languageCookieName;

		/**
		 * Constructor
		 */
		public function __construct($user = null, $config)
		{
			$this->user = $user;
			$this->config = $config;
			$this->generateLanguageCookieName();
		}

		/**
		 * Get user language
		 *
		 * @return String 	$this->language 	User language
		 */
		public function getLanguage() : String
		{
			$this->language = $this->getUserLanguage();

			if($this->language == '' || $this->language == null)
			{
				$this->language = $this->getCookieLanguage();
			}

			if($this->language == '')
			{
				$this->language = $this->getBrowserLanguage();
			}

			return $this->language;
		}

		/**
		 * Get language from user entity
		 *
		 * @return String 	[type] 	User language
		 */
		private function getUserLanguage() : String
		{
			if($this->user && $this->user->getLanguage())
			{
				return $this->user->getLanguage();
			}
			else
			{
				return '';
			}
		}

		/**
		 * Get cookie language value
		 *
		 * @return String 	[type] 	User language
		 */
		private function getCookieLanguage() : String
		{
			return ($_COOKIE[$this->languageCookieName]) ?? '';
		}

		/**
		 * Get browser language
		 *
		 * @return String 	[type] 	User language
		 */
		private function getBrowserLanguage()
		{
			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			return ($lang) ?? '';
		}

		/**
		 * Generate language cookie name
		 *
		 * @return String 	[type] 	User language
		 */
		private function generateLanguageCookieName()
		{
			$siteName = $this->config->get("site_name");
			$this->languageCookieName = ( new Slugify() )->slugify($siteName) . "-lang";
		}
	}