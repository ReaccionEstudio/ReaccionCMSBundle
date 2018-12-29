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
    		
    		// TODO: Avoid "login" and "register" urls.
    		$cookie   = new Cookie(
    							Cookies::REFERRER_URL_COOKIE_NAME, 
    							$request->getUri()
    						);

    		$response->headers->setCookie($cookie);
	    }
	}