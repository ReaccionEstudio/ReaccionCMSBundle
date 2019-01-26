<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Language;

	use Cocur\Slugify\Slugify;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;

	/**
	 * Manages the language cookie
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class LanguageCookie
	{
		/**
		 * @var ConfigServiceInterface
		 *
		 * Config service
		 */
		private $config;

		/**
		 * @var String
		 *
		 * Language cookie name
		 */
		private $languageCookieName;

		/**
		 * Constructor
		 */
		public function __construct(ConfigServiceInterface $config)
		{
			$this->config = $config;
		}

		/**
		 * Get the language cookie name
		 *
		 * @param String 	$this->languageCookieName 	Language cookie name
		 */
		public function getLanguageCookieName() : String
		{
			$this->generateLanguageCookieName();
			return $this->languageCookieName;
		}

		/**
		 * Update the language cookie value with given language
		 *
		 * @return Boolean 	true|false 	Update result
		 */
		public function setLanguageCookieValue(String $language) : Bool
		{
			$this->getLanguageCookieName();

			try
			{
				setcookie($this->languageCookieName, $language, 0, "/");
				return true;
			}
			catch(\Exception $e)
			{
				return false;
			}
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
