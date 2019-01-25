<?php

    namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

    use Services\Managers\ManagerPermissions;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use App\ReaccionEstudio\ReaccionCMSBundle\Helpers\HtmlAttributesHelper;
    use App\ReaccionEstudio\ReaccionCMSBundle\Helpers\CacheHelper;
    use App\ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;

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
         * @var RequestStack
         *
         * RequestStack
         */
        private $request;

        /** 
         * @var MenuService
         *
         * Menu service
         */
        private $menuService;

        /**
         * Constructor
         *
         * @param Router     $router     Symfony router
         */
        public function __construct(UrlGeneratorInterface $generator, RequestStack $request, MenuService $menuService)
        {
            $this->generator    = $generator;
            $this->request      = $request;
            $this->menuService  = $menuService;
        }

    	public function getFunctions() : Array
        {
            return array(
                new \Twig_SimpleFunction('getMenuLinkAttrs', array($this, 'getMenuLinkAttrs')),
                new \Twig_SimpleFunction('isMenuItemRouteActive', array($this, 'isMenuItemRouteActive')),
                new \Twig_SimpleFunction('printMenu', array($this, 'printMenu')),
                new \Twig_SimpleFunction('isBlogActive', array($this, 'isBlogActive')),
                new \Twig_SimpleFunction('getActiveRoute', array($this, 'getActiveRoute'))
            );
        }

        /**
         * Print menu HTML
         *
         * @param  String   $slug       Menu slug
         * @return String   [type]      Menu HTML
         */
        public function printMenu(String $slug = "navigation") : String
        {
            return $this->menuService->getMenu($slug);
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
            $attrs = ['href' => '#', 'data-slug' => $menuItem['value'] ];

            if($menuItem['type'] == "page" )
            {
                $url = $this->generator->generate("index_slug", [ 'slug' => $menuItem['value'] ]);
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

        /**
         * Get active routeSlug value
         *
         * @param  String   $type   Page type
         * @return String   $slug   Active routeSlug value
         */
        public function getActiveRoute($type="") : String
        {
            $slug = $this->request->getCurrentRequest()->get('slug');

            if($this->isBlogActive() || $type == "entry")
            {
                return "/blog";
            }

            return ($slug) ? $slug : "";
        }

        /**
         * Check if blog route is active
         *
         * @return Boolean  true|false  
         */
        public function isBlogActive() : Bool
        {
            $currentRoute = $this->request->getCurrentRequest()->get('_route');

            if($currentRoute == "blog") return true;
            return false;
        }

    	public function getName() : String
        {
            return 'MenuHelper';
        }
    }