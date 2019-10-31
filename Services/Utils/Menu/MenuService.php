<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class MenuService
 * @package ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Menu
 */
class MenuService
{
    /**
     * @var Request
     */
    private $request;

    /**
     * MenuService constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Get active routeSlug value
     *
     * @param  String   $type   Page type
     * @return String   $slug   Active routeSlug value
     */
    public function getActiveRoute(?string $type="") : string
    {
        $slug = $this->request->get('slug');

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
    public function isBlogActive() : bool
    {
        $currentRoute = $this->request->get('_route');

        if($currentRoute == "blog") return true;
        return false;
    }

    /**
     * Check if menu item is the same that the current route
     *
     * @param   array    $menuItem        Array with menu item data
     * @return  Boolean  true|false       Return if menu route is active
     */
    public function isMenuItemRouteActive(array $menuItem) : bool
    {
        $currentSlug = $this->request->get('slug');

        if($currentSlug == $menuItem['value'])
        {
            return true;
        }

        return false;
    }
}
