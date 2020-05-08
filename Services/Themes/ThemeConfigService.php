<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\Themes;

use Symfony\Component\Yaml\Yaml;

/**
 * Read theme file config for given template path
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ThemeConfigService
{
    /**
     * @var String
     *
     * Full template path
     */
    private $fullTemplatePath = "";

    /**
     * @var array
     *
     * Theme config parameters
     */
    private $config;

    /**
     * Constructor
     */
    public function __construct(String $fullTemplatePath)
    {
        $this->config = [];
        $this->fullTemplatePath = $fullTemplatePath;
        $this->loadConfigFile();
    }

    /**
     * Load template config.yaml file
     *
     * @return ThemeConfigService    $this        ThemeConfigService instance
     */
    public function loadConfigFile(): ThemeConfigService
    {
        $configFilePath = $this->fullTemplatePath . "/config.yaml";

        if (!file_exists($configFilePath)) {
            throw new \Error("File '" . $configFilePath . "' not found.");
        }

        $this->config = Yaml::parseFile($configFilePath);

        return $this;
    }

    /**
     * Get available views from theme config file
     *
     * @return array    $views        Available views
     */
    public function getViews(): array
    {
        $views = [];

        if (isset($this->config['theme_config']['views'])) {
            $views = $this->config['theme_config']['views'];
        }

        return $views;
    }

    /**
     * Return theme config file parameters
     *
     * @return    array    $this->config    Theme config parameters
     */
    public function getConfig(): array
    {
        return $this->config;
    }

}
