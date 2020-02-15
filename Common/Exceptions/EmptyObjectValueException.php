<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Common\Exceptions;

/**
 * Class EmptyObjectValueException
 * @package ReaccionEstudio\ReaccionCMSBundle\Common\Exceptions
 */
class EmptyObjectValueException extends \Exception
{
    /**
     * EmptyObjectValueException constructor.
     */
    public function __construct(string $objectName)
    {
        parent::__construct(
            sprintf('Defined value for "%s" object is empty or null', $objectName)
        );
    }
}
