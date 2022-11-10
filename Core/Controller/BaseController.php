<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Controller;

use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Find\PageFinder;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Adapters\PageViewAdapter;
use ReaccionEstudio\ReaccionCMSBundle\Core\Http\Response\ResponseBuilder;

/**
 * Class BaseController
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Controller
 */
class BaseController extends AbstractController
{
    /**
     * Render page by slug
     *
     * @param string $slug
     * @return Response
     */
    public function load(string $slug = ''): Response
    {
        $router = $this->get('reaccion_cms.router');
        $em = $this->getDoctrine()->getManager();

        $responseBuilder = new ResponseBuilder($router, $em);
        $responseBuilder->build($slug);

        // TODO: create template view loader
        $view = 'ReaccionCMSBundle/themes/rocket_theme/' . $responseBuilder->getPage()->getTemplate();
        $pageAdapter = new PageViewAdapter($responseBuilder->getPage());

        return $this->render($view, $pageAdapter->toArray());
    }

    /**
     * Get page object by id
     *
     * @param int $id
     */
    public function getPage(int $id) : Page
    {
        $pageFinder = new PageFinder($this->getDoctrine()->getManager());
        return $pageFinder->find($id);
    }

    /**
     * Get page object by slug
     *
     * @param string $slug
     * @return Page
     */
    public function getPageBySlug(string $slug)
    {
        $pageFinder = new PageFinder($this->getDoctrine()->getManager());
        return $pageFinder->findBySlug($slug);
    }
}
