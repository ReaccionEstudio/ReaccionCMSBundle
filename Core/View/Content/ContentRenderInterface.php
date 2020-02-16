<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\View\Content;

/**
 * Interface ContentRenderInterface
 * @package ReaccionEstudio\ReaccionCMSBundle\PrintContent
 */
interface ContentRenderInterface
{
    /**
     * @return String
     */
    public function getValue(): String;
}
