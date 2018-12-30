<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\EventListener;

	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
	use App\ReaccionEstudio\ReaccionCMSBundle\Constants\Cookies;
	use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

    /**
     * Referrer url listener
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
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

    		$url = $request->getUri();

    		if( preg_match("/_wdt/", $url) || 
    			preg_match("/assets/", $url) || 
    			preg_match("/favicon\.ico/", $url)
    		) return;

    		setcookie(Cookies::REFERRER_URL_COOKIE_NAME, $url, 0, "/");
	    }
	}