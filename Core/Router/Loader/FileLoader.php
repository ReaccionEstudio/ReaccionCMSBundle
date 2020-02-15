<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router\Loader;

use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\LoaderInterface;

/**
 * Class FileLoader
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router\Loader
 */
class FileLoader implements LoaderInterface
{
    /**
     * Routes filename
     */
    const ROUTES_FILENAME = 'reaccion_cms_routes.data';

    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string|null $type The resource type or null if unknown
     *
     * @throws \Exception If something went wrong
     */
    public function load($resource, $type = null)
    {
        // TODO: Implement load() method.
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string|null $type The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        // TODO: Implement supports() method.
    }
}
