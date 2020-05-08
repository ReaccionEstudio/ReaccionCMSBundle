<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Twig;

use Symfony\Component\Routing\Router;
use ReaccionEstudio\ReaccionCMSBundle\Core\View\Content\ContentRender;

/**
 * ContentHelper class (Twig_Extension)
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ContentHelper extends \Twig_Extension
{
    /**
     * @var Router $router
     */
    private $router;

    /**
     * ContentHelper constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return array|\Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('printContent', array($this, 'printContent'), array('is_safe' => ['html'])),
            new \Twig_SimpleFunction('getArrayTags', array($this, 'getArrayTags')),
            new \Twig_SimpleFunction('getSerializedVar', array($this, 'getSerializedVar')),
            new \Twig_SimpleFunction('substrSentence', array($this, 'substrSentence'))
        );
    }

    /**
     * Print content
     *
     * @param   array $content View page content object
     * @param   array $key Content key
     * @param   array $props Define content properties
     * @return  string                  Content as HTML
     */
    public function printContent(array $content, string $key, array $props = []): string
    {
        $contentRender = new ContentRender($this->router);
        return $contentRender->render($content, $key, $props);
    }

    /**
     * Get tags as array
     */
    public function getArrayTags($entity): array
    {
        if (method_exists($entity, 'getTags')) {
            $tags = $entity->getTags();
        } else if (gettype($entity) == "array") {
            $tags = $entity['tags'];
        }

        if (!empty($tags) && preg_match("/,/", $tags)) {
            return explode(",", $this->tags);
        }

        return [$tags];
    }

    /**
     * Get serialized variable value
     *
     * @param $serializedObject
     * @param string $key
     * @return string
     */
    public function getSerializedVar($serializedObject, string $key)
    {
        $unserializedArray = unserialize($serializedObject);

        if (isset($unserializedArray[$key])) {
            return $unserializedArray[$key];
        }

        return '';
    }

    /**
     * Slice a sentence without breaking words
     *
     * @param $sentence
     * @param int $maxLength
     * @param string $endCharacters
     * @return string
     */
    public function substrSentence($sentence, $maxLength = 100, $endCharacters = ' [...]')
    {
        if (strlen($sentence) > $maxLength) {
            if (preg_match('/\s/', $sentence)) {
                $sentence = substr($sentence, 0, strrpos(substr($sentence, 0, $maxLength), ' ')) . $endCharacters;
            } else {
                $sentence = substr($sentence, 0, $maxLength) . $endCharacters;
            }
        }

        return $sentence;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'ContentHelper';
    }
}
