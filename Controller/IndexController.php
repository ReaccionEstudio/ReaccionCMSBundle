<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use ReaccionEstudio\ReaccionCMSBundle\Core\Controller\BaseController;

/**
 * Class IndexController
 * @package ReaccionEstudio\ReaccionCMSBundle\Controller
 */
class IndexController extends BaseController
{
    /**
     * @param string $slug
     * @return Response
     */
    public function index(string $slug = '')
    {
        return parent::load($slug);
    }
}
