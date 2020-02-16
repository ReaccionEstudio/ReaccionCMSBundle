<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router;

use ReaccionEstudio\ReaccionCMSBundle\Core\Router\SchemaUpdater\SchemaUpdaterInterface;

/**
 * Class RouterSchemaUpdater
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router
 */
class RouterSchemaUpdater
{
    /**
     * @var SchemaUpdaterInterface $schemaUpdater
     */
    private $schemaUpdater;

    /**
     * RouterSchemaUpdater constructor.
     * @param SchemaUpdaterInterface $schemaUpdater
     */
    public function __construct(SchemaUpdaterInterface $schemaUpdater)
    {
        $this->schemaUpdater = $schemaUpdater;
    }

    /**
     * @return bool
     */
    public function update() : bool
    {
        return $this->schemaUpdater->update();
    }
}
