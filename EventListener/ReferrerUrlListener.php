<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\EventListener;

	use Symfony\Component\HttpFoundation\Cookie;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
	use App\ReaccionEstudio\ReaccionCMSBundle\Constants\Cookies;
	use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

	class ReferrerUrlListener
	{
	    public function onKernelResponse(FilterResponseEvent $event)
	    {
	    	$response = $event->getResponse();
    		$request  = $event->getRequest();
    		
    		// current route
    		$route  = $request->attributes->get('_route');

    		if($route == "user_login" || $route == "user_register")
    		{
    			return;
    		}

    		$cookie   = new Cookie(
    							Cookies::REFERRER_URL_COOKIE_NAME, 
    							$request->getUri()
    						);

    		$response->headers->setCookie($cookie);
	    }
	}