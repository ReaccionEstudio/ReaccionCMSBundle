<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\View\Content;

use Symfony\Component\Routing\RouterInterface;
use ReaccionEstudio\ReaccionCMSBundle\Common\Helpers\HtmlAttributesHelper;

/**
 * Class Image
 * @package ReaccionEstudio\ReaccionCMSBundle\PrintContent
 */
class Image implements ContentRenderInterface
{
    /**
     * Image constructor.
     * @param RouterInterface $router
     * @param string $contentValue
     * @param array $properties
     */
    public function __construct(RouterInterface $router, string $contentValue, array $properties = [])
    {
        $this->router = $router;
        $this->contentValue = $contentValue;
        $this->properties = $properties;
        $this->attrs = [];
    }

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

    /**
     * @return void
     */
    private function mergeProperties() : void
    {
        if(isset($this->properties['title'])) {
            $this->attrs['title'] = $this->properties['title'];
        }

        if(isset($this->properties['style'])) {
            $this->attrs['style'] = $this->properties['style'];
        }
    }

    /**
     * Get app base url
     */
    private function getAppUrl(): string
    {
        $context = $this->router->getContext();
        $port = $context->getHttpPort();
        $appUrl = $context->getScheme() . '://' . $context->getHost();

        if ($port != "8080") {
            $appUrl .= ":" . $port;
        }

        return $appUrl;
    }
}
