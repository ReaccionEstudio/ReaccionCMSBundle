<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug\Slug;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\Route;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\LoaderInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\RoutesCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Router
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router
 */
class Router
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
     */
    public function setLoader(string $loader): self
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

    }

    /**
     * Load main route
     */
    public function main()
    {
        dump($this->routes);die;
    }

    /**
     * Save routes schema in a single data storage
     */
    public function updateSchema()
    {

    }

    /**
     * Load all defined routes from using given loader
     */
    public function loadRoutes()
    {
        $routeLoader = new RouteLoader($this->loader);
        $this->routes = $routeLoader->loadRoutes();
    }
}
