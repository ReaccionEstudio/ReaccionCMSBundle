<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\Menu;

use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Common\Constants\ReaccionCMS;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
use ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
use ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeService;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Menu service.
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class MenuService
{
    /**
     * @var EntityManagerInterface
     *
     * EntityManagerInterface
     */
    private $em;

    /**
     * @var MenuContentService
     *
     * MenuContent service
     */
    private $menuContentService;

    /**
     * @var \Twig_Environment
     *
     * Twig
     */
    private $twig;

    /**
     * @var ThemeService
     *
     * Theme service
     */
    private $theme;

    /**
     * @var RequestStack
     *
     * RequestStack service
     */
    private $request;

    /**
     * Constructor
     */
    public function __construct(EntityManagerInterface $em, MenuContentService $menuContentService, \Twig_Environment $twig, ThemeService $theme, RequestStack $request)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->theme = $theme;
        $this->request = $request->getCurrentRequest();
        $this->menuContentService = $menuContentService;
    }

    /**
     * Get menu HTML
     *
     * @param  string $slug Menu slug
     * @param  string $language Menu language
     * @return string        [type]            Menu HTML value
     */
    public function getMenu(string $slug = 'navigation', string $language = ReaccionCMS::DEFAULT_LANG): string
    {
        // get menu from database
        $menu = $this->em->getRepository(Menu::class)->findOneBy(
            [
                'slug' => $slug,
                'language' => $language,
                'enabled' => true
            ]
        );

        if (empty($menu)) {
            return '';
        }

        /** @var Menu $menu */
        return $this->buildMenuHtml($menu);
    }

    /**
     * Build menu html
     *
     * @param  Menu $menu Menu entity
     * @return string    [type]        Menu Html
     */
    private function buildMenuHtml(Menu $menu): string
    {
        // get menu items as nested array
        $nestedArray = $this->menuContentService->buildNestedArray($menu, true, true);

        // get current theme views path
        $currentThemePath = $this->theme->generateRelativeTwigViewPath("layout/menu.html.twig");

        // get menu html
        return $this->twig->render($currentThemePath, ['menu' => $nestedArray]);
    }

    /**
     * Checks if given page has a relationship with any Menu
     *
     * @param  Integer $pageId Page Id
     * @return Menu|null        [type]        Menu entity
     */
    public function getPageMenu(int $pageId): ?MenuContent
    {
        return $this->em->getRepository(MenuContent::class)->getPageMenu($pageId);
    }
}
