<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug;

use Cocur\Slugify\Slugify;
use Psr\Log\InvalidArgumentException;

/**
 * Class Slug
 * @package ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug
 */
final class Slug
{
    /**
     * @var string $value Slug value
     */
    private $value;

    /**
     * Slug constructor.
     * @param string $value
     * @throws \ReaccionEstudio\ReaccionCMSBundle\Common\Exceptions\EmptyObjectValueException
     */
    public function __construct(string $value)
    {
        $this->valueIsNotEmpty($value);

        $slugify = new Slugify();
        $this->value = $slugify->slugify($value);
    }

    /**
     * @param string $value
     */
    private function valueIsNotEmpty(string $value)
    {
        if(null === $value || !strlen($value)) {
            throw new InvalidArgumentException(self::class);
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
