<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router;

use Traversable;

/**
 * A RouteCollection represents a set of Route instances.
 *
 * Class RouteCollection
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router
 */
class RouteCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * Gets the current RouteCollection as an Iterator that includes all routes.
     *
     * It implements \IteratorAggregate.
     *
     * @see all()
     *
     * @return \ArrayIterator|Route[] An \ArrayIterator object for iterating over routes
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     * Gets the number of Routes in this collection.
     *
     * @return int The number of routes
     */
    public function count()
    {
        return \count($this->routes);
    }

    /**
     * Adds a route.
     *
     * @param Route $route
     */
    public function add(Route $route) {
        // TODO
    }

    /**
     * Gets a route by given Id.
     *
     * @param int $id
     */
    public function get(int $id)
    {
        // TODO
    }

    /**
     * Removes a route by given Id.
     *
     * @param int $id
     */
    public function remove(int $id)
    {
        // TODO
    }

    /**
     * Returns all routes in this collection.
     *
     * @return Route[] An array of routes
     */
    public function all()
    {
        return $this->routes;
    }
}
