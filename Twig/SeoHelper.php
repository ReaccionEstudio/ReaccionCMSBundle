<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

use Services\Managers\ManagerPermissions;
use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;

/**
 * SeoHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class SeoHelper extends \Twig_Extension
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
            new \Twig_SimpleFunction('getTitle', array($this, 'getTitle'))
        );
    }

    /**
     * Get page <title> value
     *
     * @param   String   $seo          Array with SEO values
     * @return  String   $title        Page title
     */
    public function getTitle($seo) : String
    {
        $title = isset($seo['title']) ? $seo['title'] : '';

        $siteName = $this->config->get("site_name");

        if(strlen($siteName))
        {
            if(strlen($title)) $title .= " · ";
            $title .= $siteName;
        }

        return $title;
    }

	public function getName()
    {
        return 'SeoHelper';
    }
}