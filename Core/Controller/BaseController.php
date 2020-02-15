<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Controller;

use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Adapters\PageViewAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReaccionEstudio\ReaccionCMSBundle\Core\Http\Response\ResponseBuilder;

/**
 * Class BaseController
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Controller
 */
class BaseController extends Controller
{
    /**
     * Render page by slug
     *
     * @param string $slug
     * @return Response
     */
    public function load(string $slug = '') : Response
    {
        $router = $this->get('reaccion_cms.router');
        $em = $this->getDoctrine()->getManager();

        $responseBuilder = new ResponseBuilder($router, $em);
        $responseBuilder->build($slug, $this);

        // TODO: create template view loader
        $view = 'ReaccionCMSBundle/themes/rocket_theme/' . $responseBuilder->getPage()->getTemplate();
        $pageAdapter = new PageViewAdapter($responseBuilder->getPage());

        return $this->render($view, $pageAdapter->toArray());
    }
}
