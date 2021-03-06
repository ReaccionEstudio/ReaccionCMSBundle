<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router;

use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug\Slug;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\Route;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\LoaderInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\RoutesCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions\NotFoundRouteException;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\SchemaUpdater\FileRouterSchemaUpdater;

/**
 * Class Router
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router
 */
class Router implements RouterInterface
{
    /**
     * @var LoaderInterface $loader Loader
     */
    private $loader;

    /**
     * @var RoutesCollection $routes Collection with all routes
     */
    private $routes;

    /**
     * @var ParameterBagInterface $parameterBag
     */
    private $parameterBag;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * Router constructor.
     * @param LoaderInterface $loader
     */
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        $this->routes = new RoutesCollection();
        $this->parameterBag = $parameterBag;
        $this->em = $em;
    }

    /**
     * @param string $loader
     * @return RouterInterface
     */
    public function setLoader(string $loader): RouterInterface
    {
        $this->loader = new $loader($this->em, $this->parameterBag);
        $this->loadRoutes();
        return $this;
    }

    /**
     * Find route by slug
     * @param Slug $slug
     * @return Route
     */
    public function find(Slug $slug) : Route
    {
        $errorMssg = 'There aren\'t routes defined';

        if($this->routes->isEmpty()) {
            throw new NotFoundRouteException($errorMssg);
        }

        foreach($this->routes as $route) {
            /** @var Route $route */
            if($route->getSlug() === $slug->__toString()) {
                return $route;
            }
        }

        throw new NotFoundRouteException($errorMssg);
    }

    /**
     * Load main route
     *
     * @return Route
     * @throws NotFoundRouteException
     */
    public function main() : Route
    {
        if(null === $this->routes->getMainRoute()) {
            throw new NotFoundRouteException('Main route was not found.');
        }

        return $this->routes->getMainRoute();
    }

    /**
     * Update routes schema in a single data storage
     *
     * @return bool
     */
    public function updateSchema() : bool
    {
        $fileRouterSchemaUpdater = new FileRouterSchemaUpdater($this->em, $this->parameterBag);
        $routerSchemaUpdater = new RouterSchemaUpdater($fileRouterSchemaUpdater);
        return $routerSchemaUpdater->update();
    }

    /**
     * Load all defined routes from using given loader
     */
    public function loadRoutes() : void
    {
        $routeLoader = new RouteLoader($this->loader);
        $this->routes = $routeLoader->loadRoutes();
    }

    /**
     * @return RoutesCollection
     */
    public function getRoutes(): RoutesCollection
    {
        return $this->routes;
    }
}
