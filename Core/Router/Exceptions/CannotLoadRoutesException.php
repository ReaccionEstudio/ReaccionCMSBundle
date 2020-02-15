<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions;

/**
 * Class CannotLoadRoutesException
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions
 */
class CannotLoadRoutesException extends \Exception
{
    /**
     * CannotLoadRoutesException constructor.
     */
    public function __construct(string $message)
    {
        $message = 'It is not possible to load given routes: ' . $message;
        parent::__construct($message);
    }
}
