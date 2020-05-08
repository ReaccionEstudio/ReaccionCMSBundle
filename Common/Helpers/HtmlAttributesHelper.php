<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Common\Helpers;

/**
 * Class HtmlAttributesHelper
 * @package ReaccionEstudio\ReaccionCMSBundle\Common\Helpers
 */
class HtmlAttributesHelper
{
    /**
     * Convert array with attributes to string
     *
     * @param array $attrs
     */
    public static function toString(array $attrs): String
    {
        $attributes = "";

        foreach ($attrs as $key => $value) {
            $attributes .= ' ' . $key . '="' . $value . '"';
        }

        return $attributes;
    }
}
