<?php

    namespace ReaccionEstudio\ReaccionCMSBundle\Twig;

    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use ReaccionEstudio\ReaccionCMSBundle\Helpers\HtmlAttributesHelper;
    use ReaccionEstudio\ReaccionCMSBundle\Services\Menu\MenuService;
    use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Menu\MenuService AS MenuUtils;

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
         * @var MenuUtils
         *
         * Menu Utils service
         */
        private $menuUtils;

        /**
         * MenuHelper constructor.
         * @param UrlGeneratorInterface $generator
         * @param RequestStack $request
         * @param MenuService $menuService
         * @param MenuUtils $menuUtils
         */
        public function __construct(
            UrlGeneratorInterface $generator,
            RequestStack $request,
            MenuService $menuService,
            MenuUtils $menuUtils)
        {
            $this->generator    = $generator;
            $this->request      = $request;
            $this->menuService  = $menuService;
            $this->menuUtils    = $menuUtils;
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
        public function printMenu(string $slug = "navigation") : string
        {
            return $this->menuService->getMenu($slug);
        }

        /**
         * Get <a> HTML element attributes for menu links
         *
         * @param   array    $menuItem        Array with menu item data
         * @return  string   $stringAttrs     Attributes as string
         */
        public function getMenuLinkAttrs(array $menuItem) : string
        {
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
         * @param   array    $menuItem        Array with menu item data
         * @return  Boolean  true|false       Return if menu route is active
         */
        public function isMenuItemRouteActive(array $menuItem) : bool
        {
            return $this->menuUtils->isMenuItemRouteActive($menuItem);
        }

        /**
         * Get active routeSlug value
         *
         * @param  string   $type   Page type
         * @return string   $slug   Active routeSlug value
         */
        public function getActiveRoute(?string $type="") : string
        {
            return $this->menuUtils->getActiveRoute($type);
        }

        /**
         * Check if blog route is active
         *
         * @return boolean  true|false
         */
        public function isBlogActive() : bool
        {
            return $this->menuUtils->isBlogActive();
        }

    	public function getName() : string
        {
            return 'MenuHelper';
        }
    }
