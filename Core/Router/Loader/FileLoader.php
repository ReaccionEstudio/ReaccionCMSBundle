<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router\Loader;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

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

    /**
     * Gets the loader resolver.
     *
     * @return LoaderResolverInterface A LoaderResolverInterface instance
     */
    public function getResolver()
    {
        // TODO: Implement getResolver() method.
    }

    /**
     * Sets the loader resolver.
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // TODO: Implement setResolver() method.
    }
}
