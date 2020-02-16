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
     * @var RouterInterface
     */
    private $router;

    /**
     * ContentRender constructor.
     * @param RouterInterface $router
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

        $htmlContent = '';
        $content = $content[$key];
        $contentValue = $content['value'];

        switch ($content['type']) {
            case 'text_html':
                $htmlText = new HtmlText($contentValue);
                $htmlContent = $htmlText->getValue();
                break;

            case 'img':
            case 'image':
                $image = new Image($this->router, $contentValue);
                $htmlContent = $image->getValue();
                break;
        }

        return $htmlContent;
    }
}
