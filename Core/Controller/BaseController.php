<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReaccionEstudio\ReaccionCMSBundle\Common\Model\Slug\Slug;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\FileLoader;

/**
 * Class BaseController
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Controller
 */
class BaseController extends Controller
{
    /**
     * Render view by slug
     *
     * @param string $slug
     */
    public function load(string $slug = '') : Response
    {
        $router = $this->get('reaccion_cms.router')->setLoader(FileLoader::class);

        if(strlen($slug)) {
            $route = $router->find(new Slug($slug));
        }else{
            // Load home page
            $route = $router->main();
        }

        dump($route);
        die;

        return new Response('Ok');
    }
}
