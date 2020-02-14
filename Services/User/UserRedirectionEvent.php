<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\User;
	
	use Symfony\Bundle\FrameworkBundle\Routing\Router;
	use ReaccionEstudio\ReaccionCMSBundle\Common\Constants\Cookies;
	use ReaccionEstudio\ReaccionCMSBundle\Common\Constants\UserRedirections;
	use Symfony\Component\HttpFoundation\RedirectResponse;

	/**
	 * User redirection event
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
	final class UserRedirectionEvent
	{
		/**
		 * @var String
		 *
		 * Homepage url
		 */
		private $homepage;

		/**
		 * @var String
		 *
		 * Event name
		 */
		private $event;

		/**
		 * @var Symfony\Bundle\FrameworkBundle\Routing\Router
		 *
		 * Router service
		 */
		private $router;

		/**
		 * Constructor
		 */
		public function __construct(String $event = "", Router $router)
		{
			$this->event  	= $event;
			$this->router 	= $router;
			$this->homepage = $this->router->generate('index');
		}

		/**
		 * Exec browser redirection
		 *
		 * @return RedirectResponse	 [type]  Redirection response
		 */
		public function redirect() : RedirectResponse
		{
			if($this->event == "" || ! isset(UserRedirections::REDIRECTIONS_BY_EVENTS[$this->event]) )
			{
				// Redirect to homepage
				return new RedirectResponse($this->homepage);
			}

			// Redirect by event
			return $this->redirectByEvent();
		}

		/**
		 * Redirect by event
		 *
		 * @return RedirectResponse	 [type]  Redirection response
		 */
		private function redirectByEvent() : RedirectResponse
		{
			$redirectionData = UserRedirections::REDIRECTIONS_BY_EVENTS[$this->event];

			if($redirectionData['type'] == "route")
			{
				$routeUrl = $this->router->generate($redirectionData['value']);
				return new RedirectResponse($routeUrl);
			}
			else if($redirectionData['type'] == "referrer")
			{
				// Referrer redirection
				$referrer = $_COOKIE[Cookies::REFERRER_URL_COOKIE_NAME];

				if( ! $referrer)
				{
					return new RedirectResponse($this->homepage);	
				}

				return new RedirectResponse($referrer);
			}
			else if($redirectionData['type'] == "url")
			{
				return new RedirectResponse($redirectionData['value']);
			}
		}
	}
