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

			return $this->render("@ReaccionCMSBundle/index.html.twig");
		}
	}