<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model;

/**
 * Class Route
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model
 */
class Route
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @var bool $isMainPage
     */
    private $isMainPage;

    /**
     * @var string $language
     */
    private $language;

    /**
     * @var string $template
     */
    private $template;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

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
     * @return bool
     */
    public function isMainPage(): bool
    {
        return $this->isMainPage;
    }

    /**
     * @param bool $isMainPage
     */
    public function setIsMainPage(bool $isMainPage): void
    {
        $this->isMainPage = $isMainPage;
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
}
