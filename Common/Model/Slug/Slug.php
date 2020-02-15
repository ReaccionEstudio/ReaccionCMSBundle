<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug;

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
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
