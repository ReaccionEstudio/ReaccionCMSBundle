<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmailTemplate
 *
 * @ORM\Table(name="email_templates")
 * @ORM\Entity(repositoryClass="ReaccionEstudio\ReaccionCMSBundle\Repository\EmailTemplateRepository")
 * @ORM\HasLifecycleCallbacks
 */
class EmailTemplate
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
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="messageParams", type="text")
     */
    private $messageParams;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="fromname", type="string", length=255)
     */
    private $fromname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fromemail", type="string", length=255, nullable=true)
     */
    private $fromemail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="language", type="string", length=4, nullable=true)
     */
    private $language;

    /**
     * @var string|null
     *
     * @ORM\Column(name="template_file", type="string", length=255, nullable=true)
     */
    private $templateFile;

    /**
     * @var bool
     *
     * @ORM\Column(name="plain_text", type="boolean")
     */
    private $plainText;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

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
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessageParams()
    {
        return $this->messageParams;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function setMessageParams($messageParams)
    {
        $this->messageParams = $messageParams;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     *
     * @return self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromname()
    {
        return $this->fromname;
    }

    /**
     * @param string $fromname
     *
     * @return self
     */
    public function setFromname($fromname)
    {
        $this->fromname = $fromname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFromemail()
    {
        return $this->fromemail;
    }

    /**
     * @param string|null $fromemail
     *
     * @return self
     */
    public function setFromemail($fromemail)
    {
        $this->fromemail = $fromemail;

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
     * @return string|null
     */
    public function getTemplateFile()
    {
        return $this->templateFile;
    }

    /**
     * @param string|null $templateFile
     *
     * @return self
     */
    public function setTemplateFile($templateFile)
    {
        $this->templateFile = $templateFile;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPlainText()
    {
        return $this->plainText;
    }

    /**
     * @param bool $plainText
     *
     * @return self
     */
    public function setPlainText($plainText)
    {
        $this->plainText = $plainText;

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
}
