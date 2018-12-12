<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuContent
 *
 * @ORM\Table(name="menu_content")
 * @ORM\Entity(repositoryClass="App\ReaccionEstudio\ReaccionCMSBundle\Repository\MenuContentRepository")
 */
class MenuContent
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=6)
     */
    private $target;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", length=2)
     */
    private $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var \App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent
     *
     * @ORM\ManyToOne(targetEntity="App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $parent;

    /**
     * @var \App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu
     *
     * @ORM\ManyToOne(targetEntity="App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $menu;

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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     *
     * @return self
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

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
     * @return \App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param \App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent $parent
     *
     * @return self
     */
    public function setParent(\App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return \App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param \App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu $menu
     *
     * @return self
     */
    public function setMenu(\App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu $menu)
    {
        $this->menu = $menu;

        return $this;
    }
}