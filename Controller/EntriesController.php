<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;

	class EntriesController extends Controller
	{
		public function index(Request $request, $page = 1)
		{
			$entriesService = $this->get("reaccion_cms.entries");
			$view = $this->get("reaccion_cms.theme")->getConfigView("entries", true);

			// get data
			$entries 	= $entriesService->getEntries('en', $page);
			$categories = $this->get("reaccion_cms.entries")->getCategories();

			// view vars
			$vars = [ 
						'seo' => [], 
						'language' => 'en', 
						'name' => 'Blog',
						'entries' => $entries,
						'categories' => $categories
					];

			return $this->render($view, $vars);
		}
	}