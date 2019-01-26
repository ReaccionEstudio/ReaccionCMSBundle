<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entry
 *
 * @ORM\Table(name="entries")
 * @ORM\Entity(repositoryClass="ReaccionEstudio\ReaccionCMSBundle\Repository\EntryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Entry
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
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string|null
     *
     * @ORM\Column(name="resume", type="text", nullable=true)
     */
    private $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @var string|null
     *
     * @ORM\Column(name="language", type="string", length=4, nullable=true)
     */
    private $language;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="totalComments", type="string", length=255, nullable=true)
     */
    private $totalComments = 'a:3:{s:8:"comments";i:0;s:7:"replies";i:0;s:5:"total";i:0;}';

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
     * @var \ReaccionEstudio\ReaccionCMSBundle\Entity\Media
     *
     * @ORM\ManyToOne(targetEntity="ReaccionEstudio\ReaccionCMSBundle\Entity\Media")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="default_image_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $defaultImage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory")
     * @ORM\JoinTable(name="entries_rel_categories",
     *   joinColumns={
     *     @ORM\JoinColumn(name="entry_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *   }
     * )
     */
    private $categories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * @param string|null $resume
     *
     * @return self
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     *
     * @return self
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string|null $language
     *
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTotalComments()
    {
        return unserialize($this->totalComments);
    }

    /**
     * @param integer $enabled
     *
     * @return self
     */
    public function setTotalComments($totalComments)
    {
        $this->totalComments = serialize($totalComments);

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
     * @return \ReaccionEstudio\ReaccionCMSBundle\Entity\Media
     */
    public function getDefaultImage()
    {
        return $this->defaultImage;
    }

    /**
     * @param \ReaccionEstudio\ReaccionCMSBundle\Entity\Media $defaultImage
     *
     * @return self
     */
    public function setDefaultImage(\ReaccionEstudio\ReaccionCMSBundle\Entity\Media $defaultImage)
    {
        $this->defaultImage = $defaultImage;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $categories
     *
     * @return self
     */
    public function setCategories(\Doctrine\Common\Collections\Collection $categories)
    {
        $this->categories = $categories;

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
