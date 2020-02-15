<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug\Slug;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\FileLoader;

/**
 * Class IndexController
 * @package ReaccionEstudio\ReaccionCMSBundle\Controller
 */
class IndexController extends Controller
{
    /**
     * @param string $slug
     * @return Response
     */
    public function index(string $slug = '')
    {
        $router = $this->get('reaccion_cms.router')->setLoader(FileLoader::class);

        if(strlen($slug)) {
            $response = $router->find(new Slug($slug));
        }else{
            // Load home page
            $response = $router->main();
        }

        die;

        return new Response($view);
    }
}
