<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Response;

	class IndexController extends Controller
	{
		public function index(String $slug="")
		{
			$view = $this->get("reaccion_cms.routing")->load($slug);
			return new Response($view);
		}
	}