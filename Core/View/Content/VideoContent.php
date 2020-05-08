<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\View\Content;

use Symfony\Component\Routing\Router;
use ReaccionEstudio\ReaccionCMSBundle\Common\Helpers\HtmlAttributesHelper;

/**
 * Class VideoContent
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\View\Content
 */
class VideoContent extends BaseContentRender
{
    /**
     * Return content value
     *
     * @return String    $imageHtmlElement    Image HTML element
     */
    public function getValue(): string
    {
        // get full image url
        $fileUrl = $this->getAppUrl() . '/uploads/' . $this->contentValue;

        // merge properties
        $this->mergeProperties();

        // get attributes as string
        $stringAttrs = HtmlAttributesHelper::toString($this->attrs);

        // generate HTML
        $html = '<video ' . $stringAttrs . '>
                    <source src="' . $fileUrl . '" type="video/mp4">
                </video>';

        return $html;
    }

    /**
     * @return void
     */
    protected function mergeProperties() : void
    {
        parent::mergeProperties();

        if(isset($this->properties['controls'])) {
            $this->attrs['controls'] = '';
        }
    }
}
