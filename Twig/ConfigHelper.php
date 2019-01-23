<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

use Services\Managers\ManagerPermissions;
use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;

/**
 * ConfigHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ConfigHelper extends \Twig_Extension
{
    /**
     * Constructor
     *
     * @param ConfigServiceInterface     $config     Configuration service
     */
    public function __construct(ConfigServiceInterface $config)
    {
        $this->config = $config;
    }

	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getSiteName', array($this, 'getSiteName'))
        );
    }

    /**
     * Get site_name config value
     *
     * @return  String   $title     Site name
     */
    public function getSiteName() : String
    {
        return $this->config->get("site_name");
    }

	public function getName()
    {
        return 'ConfigHelper';
    }
}