<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ReaccionEstudio\ReaccionCMSBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $language;

    protected $nickname;

    public function __construct()
    {
        parent::__construct();

        // Default values
        $this->language = 'en';
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     *
     * @return self
     */
    public function setLanguage($language = "en")
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        if (empty($this->nickname)) {
            $this->nickname = $this->getUsername();
        }

        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     *
     * @return self
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function isAdmin() : bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }
}
