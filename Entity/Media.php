<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="App\ReaccionEstudio\ReaccionCMSBundle\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Media
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string|null
     *
     * @ORM\Column(name="large_path", type="string", length=255, nullable=true)
     */
    private $large_path;

    /**
     * @var string|null
     *
     * @ORM\Column(name="medium_path", type="string", length=255, nullable=true)
     */
    private $medium_path;

    /**
     * @var string|null
     *
     * @ORM\Column(name="small_path", type="string", length=255, nullable=true)
     */
    private $small_path;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="decimal")
     */
    private $size;

    /**
     * @var string|null
     *
     * @ORM\Column(name="large_size", type="decimal", nullable=true)
     */
    private $large_size;

    /**
     * @var string|null
     *
     * @ORM\Column(name="medium_size", type="decimal", nullable=true)
     */
    private $medium_size;

    /**
     * @var string|null
     *
     * @ORM\Column(name="small_size", type="decimal", nullable=true)
     */
    private $small_size;

    /**
     * @var string
     *
     * @ORM\Column(name="mimetype", type="string", length=255)
     */
    private $mimetype;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLargePath()
    {
        return $this->large_path;
    }

    /**
     * @param string|null $large_path
     *
     * @return self
     */
    public function setLargePath($large_path)
    {
        $this->large_path = $large_path;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMediumPath()
    {
        return $this->medium_path;
    }

    /**
     * @param string|null $medium_path
     *
     * @return self
     */
    public function setMediumPath($medium_path)
    {
        $this->medium_path = $medium_path;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSmallPath()
    {
        return $this->small_path;
    }

    /**
     * @param string|null $small_path
     *
     * @return self
     */
    public function setSmallPath($small_path)
    {
        $this->small_path = $small_path;

        return $this;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     *
     * @return self
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLargeSize()
    {
        return $this->large_size;
    }

    /**
     * @param string|null $large_size
     *
     * @return self
     */
    public function setLargeSize($large_size)
    {
        $this->large_size = $large_size;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMediumSize()
    {
        return $this->medium_size;
    }

    /**
     * @param string|null $medium_size
     *
     * @return self
     */
    public function setMediumSize($medium_size)
    {
        $this->medium_size = $medium_size;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSmallSize()
    {
        return $this->small_size;
    }

    /**
     * @param string|null $small_size
     *
     * @return self
     */
    public function setSmallSize($small_size)
    {
        $this->small_size = $small_size;

        return $this;
    }

    /**
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * @param string $mimetype
     *
     * @return self
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        $this->createdAt = new \Datetime();
    }
}
