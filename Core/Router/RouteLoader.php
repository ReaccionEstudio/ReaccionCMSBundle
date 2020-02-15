<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router;

use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\LoaderInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\RoutesCollection;

/**
 * Class RouteLoader
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router
 */
class RouteLoader
{
    /**
     * @var LoaderInterface $loader Loader
     */
    private $loader;

    /**
     * Router constructor.
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Load defined routes
     */
    public function loadRoutes() : ?RoutesCollection
    {
        // Load routes
        $routes = $this->loader->load()->getRoutes();

        // Create RoutesCollection
        return RouteCollectionFactory::createCollection($routes);
    }
}
