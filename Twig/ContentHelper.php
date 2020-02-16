<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use ReaccionEstudio\ReaccionCMSBundle\Core\View\Content\ContentRender;

/**
 * ContentHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ContentHelper extends \Twig_Extension
{
    /**
     * @var RouterInterface $router
     */
    private $router;

    /**
     * ContentHelper constructor.
     * @param RouterInterface $router
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
    public function printContent(array $content, string $key, array $props = []): String
    {
        $contentRender = new ContentRender($this->router);
        return $contentRender->render($content, $key, $props);
    }

    /**
     * Get tags as array
     *
     * @param   Doctrine Entity     $entity     Doctrine entity
     * @return  Array               [type]      Tags list array
     */
    public function getArrayTags($entity): Array
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
     * @param  any $serializedObject Serialized object
     * @param  String $key Serialized object key
     * @return any       [type]              Variable value
     */
    public function getSerializedVar($serializedObject, String $key)
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
     * @return String
     */
    public function getName(): String
    {
        return 'ContentHelper';
    }
}
