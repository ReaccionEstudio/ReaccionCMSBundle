<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\View\Content;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class ContentRender
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\View\Content
 */
class ContentRender
{
    /**
     * @var Router
     */
    private $router;

    /**
     * ContentRender constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param array $content
     * @param string $key
     * @param array $props
     * @return string
     */
    public function render(array $content, string $key, array $props = []): string
    {
        if (false === isset($content[$key])) {
            return '';
        }

        $content = $content[$key];
        $contentValue = $content['value'];

        switch ($content['type']) {
            case 'text_html':
                $contentRender = new HtmlTextContent($contentValue);
                break;

            case 'img':
            case 'image':
                $contentRender = new ImageContent($contentValue, $props);
                $contentRender->setRouter($this->router);
                break;

            case 'video':
                $contentRender = new VideoContent($contentValue, $props);
                $contentRender->setRouter($this->router);
                break;
        }

        if(empty($contentRender)) {
            return '';
        }

        return $contentRender->getValue();
    }
}
