<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\Themes;

use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;
use ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;

/**
 * Manage themes usage for pages view
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ThemeService
{
    CONST DEFAULT_CMS_THEME = 'rocket_theme';
    CONST DEFAULT_CMS_THEMES_PATH = 'templates/ReaccionCMSBundle/themes/';
    CONST DEFAULT_CMS_THEMES_TWIG_PATH = '@ReaccionCMSBundle';

    /**
     * @var ConfigServiceInterface
     *
     * Config service
     */
    private $configService;

    /**
     * @var EntityManagerInterface
     *
     * EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     *
     * Kernel project directory
     */
    private $projectDir;

    /**
     * @var string
     *
     * Full template path
     */
    private $fullTemplatePath = "";

    /**
     * @var string
     *
     * Full template view file path
     */
    private $fullTemplateViewPath = "";

    /**
     * @var string
     *
     * Current theme
     */
    private $currentTheme = "";

    /**
     * @var string
     *
     * Themes path
     */
    private $themesPath = "";

    /**
     * @var string
     *
     * Template view filename
     */
    private $templateView = "";

    /**
     * Constructor
     */
    public function __construct(EntityManagerInterface $em, ConfigServiceInterface $configService, string $projectDir = "")
    {
        $this->em = $em;
        $this->configService = $configService;
        $this->projectDir = $projectDir;

        $this->getCurrentTheme();
        $this->getThemesPath();
        $this->generateFullTemplatePath();
    }

    /**
     * Get full view path for Page entity
     *
     * @param  Page $entity Current Page entity
     * @return string        [type]        View file path for Twig
     */
    public function getPageViewPath(Page $page): string
    {
        // check if view file exists
        if (!$this->viewFileExists($page)) {
            // throw exception
            throw new \Exception('Template file not found in: ' . $this->fullTemplateViewPath);
        }

        // generate twig view file path
        return $this->generateRelativeTwigViewPath($this->templateView);
    }

    /**
     * Get fullTemplateViewPath var value
     *
     * @return string    $fullTemplateViewPath    View file path for Twig
     */
    public function getFullTemplateViewPath(): string
    {
        return $this->fullTemplateViewPath;
    }

    /**
     * Get fullTemplatePath var value
     *
     * @return string    $fullTemplatePath    Current template path
     */
    public function getFullTemplatePath(): string
    {
        return $this->fullTemplatePath;
    }

    /**
     * Generate relative view path for Twig
     *
     * @param  string $baseFilename Template filename
     * @return string    $path            Twig view relative path
     */
    public function generateRelativeTwigViewPath(String $baseFilename): string
    {
        $path = $this->themesPath;
        $path = str_replace("templates/", "", $path);
        $path .= $this->currentTheme . "/" . $baseFilename;

        return $path;
    }

    /**
     * Get theme config parameters
     *
     * @param  string $key Config parameter key
     * @return string    [type]    Config parameter value
     */
    public function getConfig(String $configKey = "")
    {
        // get view filename from theme config
        $themeConfigService = new ThemeConfigService($this->fullTemplatePath);
        $config = $themeConfigService->getConfig();

        if (empty($configKey)) {
            return $config;
        } else if (strlen($configKey) && isset($config['theme_config'][$configKey])) {
            return $config['theme_config'][$configKey];
        }
    }

    /**
     * Get config view parameter value
     *
     * @param  string $key Config parameter key
     * @param  Boolean $generateRelativeTwigViewPath Get relative twig view path
     * @return string    $view                                View path
     */
    public function getConfigView(string $key = '', bool $generateRelativeTwigViewPath = false): string
    {
        $view = '';
        $configViews = $this->getConfig("views");

        if ($configViews && strlen($key) && isset($configViews[$key])) {
            $view = $configViews[$key];
        }

        if ($generateRelativeTwigViewPath == true) {
            $view = $this->generateRelativeTwigViewPath($view);
        }

        return $view;
    }

    /**
     * Check if theme view file exists
     *
     * @param  Page $page Current page entity
     * @return Boolean        true|false        File exists?
     */
    private function viewFileExists(Page $page): bool
    {
        if ($page->getType() == "entry") {
            // get view filename from theme config
            $view = $this->getConfigView("entry");

            // set templateView value
            $this->templateView = $view ?? "entry.html.twig";
        } else {
            $this->templateView = $page->getTemplateView();
        }

        // set view file path
        $this->fullTemplateViewPath = $this->fullTemplatePath . $this->templateView;

        if (file_exists($this->fullTemplateViewPath)) {
            return true;
        }

        return false;
    }

    /**
     * Generate fullTemplatePath var value
     */
    private function generateFullTemplatePath(): void
    {
        $this->fullTemplatePath = $this->projectDir . "/" . $this->themesPath . $this->currentTheme . "/";
    }

    /**
     * Get current theme name defined in the configuration table
     */
    private function getCurrentTheme(): void
    {
        $defaultThemeConfig = $this->configService->get("current_theme");
        $this->currentTheme = ($defaultThemeConfig) ? $defaultThemeConfig : self::DEFAULT_CMS_THEME;
    }

    /**
     * Get current themes path defined in the configuration table
     */
    private function getThemesPath(): void
    {
        $themesPathConfig = $this->configService->get("themes_path");
        $this->themesPath = ($themesPathConfig) ? $themesPathConfig : self::DEFAULT_CMS_THEMES_PATH;
    }
}
