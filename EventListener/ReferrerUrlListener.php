<?php

namespace ReaccionEstudio\ReaccionCMSBundle\EventListener;

use ReaccionEstudio\ReaccionCMSBundle\Common\Constants\Cookies;

/**
 * Referrer url listener
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ReferrerUrlListener
{
    /*
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        // current route
        $route = $request->attributes->get('_route');
        $referer = $request->headers->get('referer');

        if ($route == "user_login" || $route == "user_register" || preg_match("/login/", $referer)) {
            return;
        }

        if ($referer == null) return;

        setcookie(Cookies::REFERRER_URL_COOKIE_NAME, $referer, 0, "/");
    }
    */
}
