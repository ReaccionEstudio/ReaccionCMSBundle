<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router;

use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Helps add and import routes into a RouteCollection.
 *
 * Class RouteCollectionBuilder
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router
 */
class RouteCollectionBuilder
{
    /**
     * Routes filename
     */
    const ROUTES_FILENAME = 'reaccion_cms_routes.data';

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * RouteCollectionBuilder constructor.
     * @param LoaderInterface|null $loader
     */
    public function __construct(LoaderInterface $loader = null)
    {
        $this->loader = $loader;
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->routeCollection;
    }
}
