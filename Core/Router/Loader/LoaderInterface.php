<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader;

/**
 * LoaderInterface is the interface implemented by route loader classes.
 */
interface LoaderInterface
{
    /**
     * Loads defined routes.
     */
    public function load() : LoaderInterface;

    /**
     * Get loaded routes
     * @return array
     */
    public function getRoutes(): array;
}
