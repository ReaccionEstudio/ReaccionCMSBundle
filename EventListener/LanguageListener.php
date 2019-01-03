<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\EventListener;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpKernel\Event\GetResponseEvent;
    use App\ReaccionEstudio\ReaccionCMSBundle\Services\Language\LanguageService;

    /**
     * Language|Locale listener
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
	class LanguageListener
	{
        public function __construct(LanguageService $language)
        {
            $this->language = $language;
        }

	    public function onKernelRequest(GetResponseEvent $event)
	    {
    		$request  = $event->getRequest();
            $route = $request->get('_route');

            if( ! preg_match("/reaccion_cms_admin_/", $route) ) return;

            // get locale
            $locale = $this->language->getLanguage();

            // set locale
    		$request->setLocale($locale);
	    }
	}