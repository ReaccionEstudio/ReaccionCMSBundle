<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model;

/**
 * Class Page
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Page
 */
class Page
{
    /**
     * @var string $name Name
     */
    private $name;

    /**
     * @var string $slug Slug
     */
    private $slug;

    /**
     * @var bool $isEnabled Indicates if page is enabled
     */
    private $isEnabled;

    /**
     * @var string $language Language
     */
    private $language;

    /**
     * @var string $template View template to use
     */
    private $template;

    /**
     * @var PageContentCollection $content Page content items
     */
    private $content;

    /**
     * @var object $seo Seo content
     */
    private $seo;


}
