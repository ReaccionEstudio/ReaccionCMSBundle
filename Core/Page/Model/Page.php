<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model;

use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Collections\PageContentCollection;

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
     * @var Seo $seo Seo content
     */
    private $seo;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return PageContentCollection
     */
    public function getContent(): PageContentCollection
    {
        return $this->content;
    }

    /**
     * @param PageContentCollection $content
     */
    public function setContent(PageContentCollection $content): void
    {
        $this->content = $content;
    }

    /**
     * @return Seo
     */
    public function getSeo(): Seo
    {
        return $this->seo;
    }

    /**
     * @param Seo $seo
     */
    public function setSeo(Seo $seo): void
    {
        $this->seo = $seo;
    }
}
