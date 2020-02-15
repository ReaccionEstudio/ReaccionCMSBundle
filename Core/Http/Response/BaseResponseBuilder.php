<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Http\Response;

use ReaccionEstudio\ReaccionCMSBundle\Core\Controller\BaseController;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Page;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Router;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\Route;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page as PageEntity;

/**
 * Class BaseResponseBuilder
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Controller
 */
abstract class BaseResponseBuilder
{
    /**
     * Build ReaccionCMS controller response
     *
     * @param string $slug
     * @param BaseController $controller
     * @return mixed
     */
    final public function build(string $slug = '', BaseController $controller)
    {
        $route = $this->getRoute($slug);
        $data = $this->getData($route);
        $this->composePage($data);
    }

    /**
     * Get route to be loaded
     *
     * @param Router $router
     * @return Router
     */
    abstract public function getRoute(string $slug): Route;

    /**
     * Get data from route
     *
     * @param Route $route
     * @return mixed
     */
    abstract public function getData(Route $route): PageEntity;

    /**
     * Compose Page object
     *
     * @param PageEntity $data
     * @return Page
     */
    abstract public function composePage(PageEntity $data): void;

    /**
     * @return Page
     */
    abstract public function getPage(): Page;
}
