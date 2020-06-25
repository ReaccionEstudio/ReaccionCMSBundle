<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions;

/**
 * Class NotFoundRouteException
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions
 */
class NotFoundRouteException extends \Exception
{
    /**
     * NotFoundRouteException constructor.
     */
    public function __construct(string $id)
    {
        $message = sprintf('Route "%s" not found', $id);
        parent::__construct($message);
    }
}
