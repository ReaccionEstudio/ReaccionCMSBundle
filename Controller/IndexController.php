<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewVarsFactory;
	use App\ReaccionEstudio\ReaccionCMSBundle\EntryView\EntryViewVarsFactory;

	class IndexController extends Controller
	{
		public function index(Request $request, $slug="")
		{
			$routingService = $this->get("reaccion_cms.routing");
			$entry = null;
			$page  = $routingService->loadPage($slug);

			if($page !== null)
			{
				// load page for slug value
				$pageViewVars = PageViewVarsFactory::makePageViewVars(
														$page, 
														$this->get("reaccion_cms.menu"), 
														$this->get("reaccion_cms.entries")
													);
				return $this->render($page->getTemplateView(), $pageViewVars->getVars());
			}

			if($page == null)
			{
				// check if it is a slug's entry
				$entry = $routingService->loadEntry($slug);
				
				if(isset($entry['entry']))
				{
					// load page for slug value
					$entryViewVars = EntryViewVarsFactory::makeEntryViewVars(
															$entry['entry'], 
															$this->get("reaccion_cms.menu"), 
															$this->get("reaccion_cms.entries")
														);
					return $this->render($entry['view'], $entryViewVars->getVars());
				}
			}

			if($page == null && $entry == [])
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