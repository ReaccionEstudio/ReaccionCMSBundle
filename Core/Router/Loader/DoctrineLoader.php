<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router\Loader;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

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
