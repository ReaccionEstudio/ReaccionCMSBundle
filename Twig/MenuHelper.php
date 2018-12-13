<?php

    namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

    use Services\Managers\ManagerPermissions;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use App\ReaccionEstudio\ReaccionCMSBundle\Helpers\HtmlAttributesHelper;

    /**
     * MenuHelper class (Twig_Extension)
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
    class MenuHelper extends \Twig_Extension
    {
        /**
         * @var UrlGeneratorInterface
         *
         * UrlGeneratorInterface
         */
        private $generator;

        /**
         * Constructor
         *
         * @param Router     $router     Symfony router
         */
        public function __construct(UrlGeneratorInterface $generator, RequestStack $request)
        {
            $this->generator = $generator;
            $this->request  = $request;
        }

    	public function getFunctions() : Array
        {
            return array(
                new \Twig_SimpleFunction('getMenuLinkAttrs', array($this, 'getMenuLinkAttrs')),
                new \Twig_SimpleFunction('isMenuItemRouteActive', array($this, 'isMenuItemRouteActive'))
            );
        }

        /**
         * Get <a> HTML element attributes for menu links
         *
         * @param   Array    $menuItem        Array with menu item data
         * @return  String   $stringAttrs     Attributes as string
         */
        public function getMenuLinkAttrs(Array $menuItem) : String
        {
            $stringAttrs = '';
            $attrs = ['href' => '#'];

            if($menuItem['type'] == "page" )
            {
                $url = $this->generator->generate("index_slug", [ 'slug' => $menuItem['value'] ]);
                $attrs['data-slug'] = $menuItem['value'];
            }
            elseif($menuItem['type'] == "url")
            {
                $url = $menuItem['value'];
            }

            if( ! empty($url))
            {
                $attrs['href'] = $url;
            }

            if( isset($menuItem['target']))
            {
                $attrs['target'] =  $menuItem['target'];
            }

            if( isset($menuItem['name']))
            {
                $attrs['title'] = $menuItem['name'];
            }

            // Get attributes as string
            $stringAttrs = (new HtmlAttributesHelper($attrs))->getAttributesAsString();

            return $stringAttrs;
        }

        /** 
         * Check if menu item is the same that the current route
         *
         * @param   Array    $menuItem        Array with menu item data
         * @return  Boolean  true|false       Return if menu route is active
         */
        public function isMenuItemRouteActive(Array $menuItem) : bool
        {
            $currentSlug = $this->request->getCurrentRequest()->get('slug');

            if($currentSlug == $menuItem['value']) 
            {
                return true;
            }

            return false;
        }

    	public function getName() : String
        {
            return 'MenuHelper';
        }
    }