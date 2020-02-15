<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router;


use ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug\Slug;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\Route;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\RoutesCollection;

/**
 * Interface RouterInterface
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router
 */
interface RouterInterface
{
    /**
     * @param string $loader
     * @return RouterInterface
     */
    public function setLoader(string $loader): RouterInterface;

    /**
     * Find route by slug
     * @param Slug $slug
     * @return Route
     */
    public function find(Slug $slug): Route;

    /**
     * Load main route
     * @return Route
     */
    public function main(): Route;

    /**
     * Save routes schema in a single data storage
     * @return bool
     */
    public function updateSchema(): bool;

    /**
     * Load all defined routes from using given loader
     */
    public function loadRoutes(): void;

    /**
     * @return RoutesCollection
     */
    public function getRoutes(): RoutesCollection;
}
