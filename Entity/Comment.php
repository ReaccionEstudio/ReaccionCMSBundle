<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="ReaccionEstudio\ReaccionCMSBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Comment
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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
     * @var \ReaccionEstudio\ReaccionCMSBundle\Entity\Entry
     *
     * @ORM\ManyToOne(targetEntity="ReaccionEstudio\ReaccionCMSBundle\Entity\Entry")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entry_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $entry;

    /**
     * @var \ReaccionEstudio\ReaccionCMSBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="ReaccionEstudio\ReaccionCMSBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \ReaccionEstudio\ReaccionCMSBundle\Entity\Comment
     *
     * @ORM\ManyToOne(targetEntity="ReaccionEstudio\ReaccionCMSBundle\Entity\Comment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $reply;

    /**
     * @var \ReaccionEstudio\ReaccionCMSBundle\Entity\Comment
     *
     * @ORM\ManyToOne(targetEntity="ReaccionEstudio\ReaccionCMSBundle\Entity\Comment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="root_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $root;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function setUpdatedValue()
    {
        $this->updatedAt = new \DateTime();
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
     * @return \ReaccionEstudio\ReaccionCMSBundle\Entity\Entry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * @param \ReaccionEstudio\ReaccionCMSBundle\Entity\Entry $entry
     *
     * @return self
     */
    public function setEntry(\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry $entry)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * @return \ReaccionEstudio\ReaccionCMSBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \ReaccionEstudio\ReaccionCMSBundle\Entity\User $user
     *
     * @return self
     */
    public function setUser(\ReaccionEstudio\ReaccionCMSBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \ReaccionEstudio\ReaccionCMSBundle\Entity\Comment
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * @param \ReaccionEstudio\ReaccionCMSBundle\Entity\Comment $reply
     *
     * @return self
     */
    public function setReply(\ReaccionEstudio\ReaccionCMSBundle\Entity\Comment $reply)
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * @return \ReaccionEstudio\ReaccionCMSBundle\Entity\Comment
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param \ReaccionEstudio\ReaccionCMSBundle\Entity\Comment $root
     *
     * @return self
     */
    public function setRoot(\ReaccionEstudio\ReaccionCMSBundle\Entity\Comment $root)
    {
        $this->root = $root;

        return $this;
    }
}
