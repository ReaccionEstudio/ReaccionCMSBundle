<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class IndexController extends Controller
	{
		public function index($slug="")
		{
			if( ! strlen($slug))
			{
				// TODO: Get main route defined in database
			}
			else
			{
				// TODO: Load page for requested slug
			}

			// TODO: if page not found and main route is not defined then show default view
			$cmsVersion = $this->getParameter("reaccion_cms.version");

			return $this->render("@ReaccionCMSBundle/index.html.twig", [
				'cmsVersion' => $cmsVersion
			]);
		}
	}