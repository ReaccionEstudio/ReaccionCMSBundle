<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\View\Content;

use Symfony\Component\Routing\Router;

/**
 * Class BaseContentRender
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\View\Content
 */
abstract class BaseContentRender
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $contentValue;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var array
     */
    protected $attrs;

    /**
     * Image constructor.
     * @param Router $router
     * @param string $contentValue
     * @param array $properties
     */
    public function __construct(string $contentValue, array $properties = [])
    {
        $this->router = null;
        $this->contentValue = $contentValue;
        $this->properties = $properties;
        $this->attrs = [];
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->contentValue;
    }

    /**
     * @return void
     */
    protected function mergeProperties() : void
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
    protected function getAppUrl(): string
    {
        $context = $this->router->getContext();
        $port = $context->getHttpPort();
        $appUrl = $context->getScheme() . '://' . $context->getHost();

        if ($port != "8080") {
            $appUrl .= ":" . $port;
        }

        return $appUrl;
    }

    /**
     * @param Router $router
     */
    public function setRouter(Router $router): self
    {
        $this->router = $router;
        return $this;
    }
}
