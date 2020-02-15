<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router\Loader;

use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\LoaderInterface;

/**
 * Class DoctrineLoader
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router\Loader
 */
class DoctrineLoader implements LoaderInterface
{
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
