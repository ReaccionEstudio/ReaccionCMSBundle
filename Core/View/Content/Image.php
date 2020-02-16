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
     * Constructor
     *
     * @param String $contentValue Content value
     * @param array $properties Image properties
     */
    public function __construct(RouterInterface $router, String $contentValue, Array $properties = [])
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

        // get attributes as string
        $stringAttrs = HtmlAttributesHelper::toString($this->attrs);

        // generate HTML img element
        $imageHtmlElement = '<img ' . $stringAttrs . ' />';

        return $imageHtmlElement;
    }

    /**
     * Get app base url
     *
     * @return String    $appUrl    App base url
     */
    private function getAppUrl(): String
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
