<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router;

use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Slug;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\Route;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\LoaderInterface;

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
     * Router constructor.
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Find route by slug
     * @param Slug $slug
     * @return Route
     */
    public function findRoute(Slug $slug) : Route
    {

    }
}
