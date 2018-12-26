<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity(repositoryClass="App\ReaccionEstudio\ReaccionCMSBundle\Repository\PageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Page
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $isEnabled = true;

    /**
     * @var string|null
     *
     * @ORM\Column(name="options", type="text", nullable=true)
     */
    private $options;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="main_page", type="boolean", nullable=true)
     */
    private $mainPage = null;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", options={"default"="en"})
     */
    private $language = 'en';

    /**
     * @var string
     *
     * @ORM\Column(name="template_view", type="string", length=255)
     */
    private $templateView = "page.html.twig";

    /**
     * @var string|null
     *
     * @ORM\Column(name="seo_title", type="string", length=255, nullable=true)
     */
    private $seoTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="seo_description", type="text", nullable=true)
     */
    private $seoDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="seo_keywords", type="string", length=255, nullable=true)
     */
    private $seoKeywords;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent", mappedBy="page")
     */
    private $content;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->content = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($slug);

        return $this;
    }

    /**
     * @return bool
     */
    public function isIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     *
     * @return self
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string|null $options
     *
     * @return self
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMainPage()
    {
        return $this->mainPage;
    }

    /**
     * @param bool $mainPage
     *
     * @return self
     */
    public function setMainPage($mainPage)
    {
        $this->mainPage = $mainPage;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateView()
    {
        return $this->templateView;
    }

    /**
     * @param string $templateView
     *
     * @return self
     */
    public function setTemplateView($templateView)
    {
        $this->templateView = $templateView;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * @param string|null $seoTitle
     *
     * @return self
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * @param string|null $seoDescription
     *
     * @return self
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * @param string|null $seoKeywords
     *
     * @return self
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $content
     *
     * @return self
     */
    public function setContent(\Doctrine\Common\Collections\Collection $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        $this->createdAt = new \Datetime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this->updatedAt = new \Datetime();
    }
}
