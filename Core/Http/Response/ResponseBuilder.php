<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Http\Response;

use Doctrine\ORM\EntityManagerInterface;

use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Router;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Seo;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Page;
use ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug\Slug;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\Route;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page as PageEntity;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\FileLoader;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Collections\PageContentCollection;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions\NotFoundRouteDataException;

/**
 * Class ResponseBuilder
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Controller
 */
class ResponseBuilder extends BaseResponseBuilder
{
    /**
     * @var Router $router
     */
    private $router;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var Page $page
     */
    private $page;

    /**
     * @var Route $route
     */
    private $route;

    /**
     * ResponseBuilder constructor.
     * @param Router $router
     */
    public function __construct(Router $router, EntityManagerInterface $em)
    {
        $this->page = null;
        $this->route = null;
        $this->em = $em;
        $this->router = $router;
        $this->router->setLoader(FileLoader::class);
    }

    /**
     * Get route to be loaded
     *
     * @param Router $router
     * @return Router
     */
    public function getRoute(string $slug): Route
    {
        if (strlen($slug)) {
            $this->route = $this->router->find(new Slug($slug));
        } else {
            // Load home page
            $this->route = $this->router->main();
        }

        return $this->route;
    }

    /**
     * Get data from route
     *
     * @param Route $route
     * @return mixed
     */
    public function getData(Route $route): PageEntity
    {
        return $this->em->getRepository(PageEntity::class)->find($route->getId());
    }

    /**
     * Compose Page object
     *
     * @param $data
     * @return Page
     * @throws NotFoundRouteDataException
     */
    public function composePage(PageEntity $data): void
    {
        if (null === $data) {
            throw new NotFoundRouteDataException();
        }

        // Page Content
        $pageContent = new PageContentCollection($data->getContent()->toArray());

        // Seo
        $seo = new Seo();
        $seo->setTitle($data->getSeoTitle());
        $seo->setDescription($data->getSeoDescription());
        $seo->setKeywords($data->getSeoKeywords());

        // Page
        $this->page = new Page();
        $this->page->setName($data->getName());
        $this->page->setSlug($data->getSlug());
        $this->page->setLanguage($data->getLanguage());
        $this->page->setTemplate($data->getTemplateView());
        $this->page->setContent($pageContent);
        $this->page->setSeo($seo);
    }

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }
}
