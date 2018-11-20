<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewVarsFactory;

	class IndexController extends Controller
	{
		public function index($slug="")
		{
			$routingService = $this->get("reaccion_cms.routing");
			$page = $routingService->loadPage($slug);

			if($page !== null)
			{
				// load page for slug value
				$pageViewVars = PageViewVarsFactory::makePageViewVars($page);
				return $this->render($page->getTemplateView(), $pageViewVars->getVars());
			}
			else
			{
				// load 404 error page
				$viewPath = $routingService->loadErrorPage(404);
				return $this->render($viewPath, [ 'slug' => $slug ]);
			}

			// default ReaccionCMSBundle view
			$cmsVersion = $this->getParameter("reaccion_cms.version");
			return $this->render("@ReaccionCMSBundle/index.html.twig", [ 'cmsVersion' => $cmsVersion ]);
		}
	}