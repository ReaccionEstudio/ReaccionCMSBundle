<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\View\Content;

use ReaccionEstudio\ReaccionCMSBundle\Common\Helpers\HtmlAttributesHelper;

/**
 * Class Image
 * @package ReaccionEstudio\ReaccionCMSBundle\PrintContent
 */
class ImageContent extends BaseContentRender
{
    /**
     * Return content value
     *
     * @return String    $imageHtmlElement    Image HTML element
     */
    public function getValue(): String
    {
        // get full image url
        $imageUrl = $this->getAppUrl() . '/uploads/' . $this->contentValue;

        // create image attributes
        $this->attrs['src'] = $imageUrl;
        $this->attrs['alt'] = basename($this->contentValue);

        // merge properties
        $this->mergeProperties();

        // get attributes as string
        $stringAttrs = HtmlAttributesHelper::toString($this->attrs);

        // generate HTML img element
        return '<img ' . $stringAttrs . ' />';
    }
}
