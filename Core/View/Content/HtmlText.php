<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\View\Content;

/**
 * Class HtmlText
 * @package ReaccionEstudio\ReaccionCMSBundle\PrintContent
 */
class HtmlText implements ContentRenderInterface
{
    /**
     * @var String $contentValue
     */
    private $contentValue;

    /**
     * Constructor
     *
     * @param String $contentValue Content value
     */
    public function __construct(String $contentValue)
    {
        $this->contentValue = $contentValue;
    }

    /**
     * Return content value
     *
     * @return String    $this->contentValue    Content value
     */
    public function getValue(): String
    {
        return $this->contentValue;
    }
}
