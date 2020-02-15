<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions;

use ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug\Slug;

/**
 * Class NotFoundRouteDataException
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions
 */
class NotFoundRouteDataException extends \Exception
{
    /**
     * NotFoundRouteDataException constructor.
     * @param Slug $slug
     */
    public function __construct(Slug $slug = null)
    {
        $message = 'Data was not found for route';

        if(null !== $slug){
            $message .= ': %s';
            $message = sprintf($message, $slug);
        }
        parent::__construct($message);
    }
}
