<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class IndexController extends Controller
	{
		public function index($slug="")
		{
			$routingService = $this->get("reaccion_cms.routing");
			$page = $routingService->loadPage($slug);

			if($page !== null)
			{
				return $this->render($page->getTemplateView(), 
					[
						'seoTitle' => $page->getSeoTitle(),
						'seoDescription' => $page->getSeoDescription(),
						'seoKeywords' => $page->getSeoKeywords(),
						'content' => $page->getContent()
					]
				);
			}
			else
			{
				// load 404 error page
				$viewPath = $routingService->loadErrorPage(404);
				return $this->render($viewPath, [ 'slug' => $slug ]);
			}

			// default ReaccionCMSBundle view
			$cmsVersion = $this->getParameter("reaccion_cms.version");

			return $this->render("@ReaccionCMSBundle/index.html.twig", 
				[
					'cmsVersion' => $cmsVersion
				]
			);
		}
	}