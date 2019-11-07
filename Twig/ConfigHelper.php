<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Twig;

use Services\Managers\ManagerPermissions;
use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;

/**
 * ConfigHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ConfigHelper extends \Twig_Extension
{
    /**
     * List of allowed config keys to load using Twig
     */
    CONST ALLOWED_KEYS = [
        'site_name',
        'show_languages_switcher',
        'user_registration'
    ];

    /**
     * @var ConfigServiceInterface
     */
    private $config;

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
            new \Twig_SimpleFunction('getConfig', array($this, 'getConfig'))
        );
    }

    /**
     * Get config param value
     *
     * @param string $key
     * @return String
     */
    public function getConfig(string $key) : ?String
    {
        if(in_array($key, self::ALLOWED_KEYS))
        {
            return $this->config->get($key);
        }

        return null;
    }

	public function getName()
    {
        return 'ConfigHelper';
    }
}
