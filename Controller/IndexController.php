<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Response;

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
        public function index(string $slug='')
		{
			$view = $this->get('reaccion_cms.routing')->load($slug);
			return new Response($view);
		}
	}
