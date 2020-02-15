<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug;

use Cocur\Slugify\Slugify;
use ReaccionEstudio\ReaccionCMSBundle\Common\Exceptions\EmptyObjectValueException;

/**
 * Class Slug
 * @package ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug
 */
class Slug
{
    /**
     * @var string $value Slug value
     */
    private $value;

    /**
     * Slug constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->valueIsNotEmpty($value);

        $slugify = new Slugify();
        $this->value = $slugify->slugify($value);
    }

    /**
     * @param string $value
     * @throws EmptyObjectValueException
     */
    private function valueIsNotEmpty(string $value)
    {
        if(null === $value || !strlen($value)) {
            throw new EmptyObjectValueException(self::class);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
