<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

use Services\Managers\ManagerPermissions;
use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;

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
     * @param ConfigService     $config     Configuration service
     */
    public function __construct(ConfigService $config)
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
     * @param   String   $seoTitle     Seo title saved in database for current page
     * @return  String   $title        Page title
     */
    public function getTitle($seoTitle) : String
    {
        $title = $seoTitle;

        $siteName = $this->config->get("site_name");

        if(strlen($siteName))
        {
            $title .= " - " . $siteName;
        }

        return $title;
    }

	public function getName()
    {
        return 'SeoHelper';
    }
}