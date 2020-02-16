<?php


namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\SchemaUpdater;

/**
 * Interface SchemaUpdaterInterface
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router\SchemaUpdater
 */
interface SchemaUpdaterInterface
{
    /**
     * @return bool
     */
    public function update() : bool;
}
