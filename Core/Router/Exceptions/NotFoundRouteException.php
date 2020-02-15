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
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
