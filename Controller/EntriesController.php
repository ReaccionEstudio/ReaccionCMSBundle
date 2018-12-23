<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use Symfony\Component\HttpFoundation\Request;

	class EntriesController extends Controller
	{
		/**
		 * Blog home - Entries list
		 */
		public function index(Request $request, Int $page = 1)
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

		/**
		 * Blog - Category entries list
		 */
		public function category(Request $request, String $category="", Int $page = 1)
		{
			$currentCategoryEntity = null;
			$em = $this->getDoctrine()->getManager();
			$entriesService = $this->get("reaccion_cms.entries");
			$view = $this->get("reaccion_cms.theme")->getConfigView("entries", true);

			// get categories
			$categories = $this->get("reaccion_cms.entries")->getCategories();

			// get current category entity
			foreach($categories as $categoryEntity)
			{
				if($categoryEntity->getSlug() == $category)
				{
					$currentCategoryEntity = $categoryEntity;
				}
			}

			// get entries
			$entriesFilters = ['categories' => [ $category ] ];
			$entries = $em->getRepository(Entry::class)->getEntries($entriesFilters);

			// entries pagination
			$paginator = $this->get('knp_paginator');
		    $entries = $paginator->paginate(
		        $entries,
		        $page,
		        $this->getParameter("pagination_page_limit")
		    );

			// view vars
			$vars = [ 
						'seo' => [], 
						'language' => 'en', 
						'name' => 'Blog',
						'entries' => $entries,
						'categories' => $categories,
						'currentCategory' => $currentCategoryEntity
					];

			return $this->render($view, $vars);
		}
	}