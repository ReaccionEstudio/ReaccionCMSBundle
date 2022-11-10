<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Controller\Entries;

use ReaccionEstudio\ReaccionCMSBundle\Services\Comment\CommentService;
use ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;

/**
 * Class EntriesController
 * @package ReaccionEstudio\ReaccionCMSBundle\Controller\Entries
 */
class EntriesController extends AbstractController
{
    /**
     * Blog home - Entries list
     * @param Request $request
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $page = 1)
    {
        $entriesService = $this->get("reaccion_cms.entries");
        $view = $this->get("reaccion_cms.theme")->getConfigView("entries", true);

        // get data
        $entries = $entriesService->getEntries('en', $page);
        $categories = $this->get("reaccion_cms.entries")->getCategories();

        // view vars
        $vars = [
            'seo' => [],
            'name' => 'Blog',
            'entries' => $entries,
            'categories' => $categories,
            'type' => 'entry'
        ];

        return $this->render($view, $vars);
    }

    /**
     * @param Entry $entry
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Entry $entry)
    {
        $view = $this->get("reaccion_cms.theme")->getConfigView("entry", true);

        /** @var EntryService $entryService */
        $entryService = $this->get('reaccion_cms.entries');

        /** @var CommentService $commentService */
        $commentService = $this->get('reaccion_cms.comment');

        $categories = $entryService->getCategories();
        $navLinks = $entryService->getPreviousAndNextEntries($entry);
        $comments = $commentService->getComments($entry->getId());

        // view vars
        $vars = [
            'seo' => [
                'title' => $entry->getName()
            ],
            'name' => $entry->getName() ,
            'entry' => $entry,
            'categories' => $categories,
            'comments' => $comments
        ];

        $vars = array_merge($vars, $navLinks);
        return $this->render($view, $vars);
    }

    /**
     * Blog - List entries filtering by category
     */
    public function category(string $category, int $page = 1)
    {
        $currentCategoryEntity = null;
        $em = $this->getDoctrine()->getManager();
        $view = $this->get("reaccion_cms.theme")->getConfigView("entries", true);

        // get categories
        $categories = $this->get("reaccion_cms.entries")->getCategories();

        // get current category entity
        foreach ($categories as $categoryEntity) {
            if ($categoryEntity->getSlug() == $category) {
                $currentCategoryEntity = $categoryEntity;
            }
        }

        // get entries
        $entriesFilters = ['categories' => [$category]];
        $entries = $em->getRepository(Entry::class)->getEntries($entriesFilters);

        // load pagination limit parameter from config
        $config = $this->get("reaccion_cms.config");
        $limit = ($config->get("entries_list_pagination_limit") > 0)
            ? $config->get("entries_list_pagination_limit")
            : 10;

        // entries pagination
        $entries = $this->get('knp_paginator')->paginate($entries, $page, $limit);

        // view vars
        $vars = [
            'seo' => [],
            'name' => 'Blog',
            'entries' => $entries,
            'categories' => $categories,
            'currentCategory' => $currentCategoryEntity,
            'type' => 'entry'
        ];

        return $this->render($view, $vars);
    }

    /**
     * Blog - List entries filtering by tag
     */
    public function tag(Request $request, string $tag = "", int $page = 1)
    {
        $currentCategoryEntity = null;
        $em = $this->getDoctrine()->getManager();
        $view = $this->get("reaccion_cms.theme")->getConfigView("entries", true);

        // get categories
        $categories = $this->get("reaccion_cms.entries")->getCategories();

        // get entries
        $entriesFilters = ['tag' => $tag];
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
            'name' => 'Blog',
            'entries' => $entries,
            'categories' => $categories,
            'currentTag' => $tag,
            'type' => 'entry'
        ];

        return $this->render($view, $vars);
    }
}
