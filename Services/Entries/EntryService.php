<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\Entries;

use Knp\Component\Pager\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;

/**
 * Entry service.
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class EntryService
{
    /**
     * @var EntityManagerInterface
     *
     * EntityManager
     */
    private $em;

    /**
     * @var Paginator
     *
     * KNP PaginatorBundle service
     */
    private $paginator;

    /**
     * @var ConfigServiceInterface
     *
     * Config service
     */
    private $config;

    /**
     * Constructor
     */
    public function __construct(EntityManagerInterface $em, Paginator $paginator, ConfigServiceInterface $config)
    {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->config = $config;
    }

    /**
     * Get entries for current page language
     * @param string $language
     * @param int $page
     * @return SlidingPagination
     */
    public function getEntries(string $language = "en", int $page = 1): SlidingPagination
    {
        // TODO: KISS
        // get entries
        $filters = ['language' => $language];
        $entries = $this->em->getRepository(Entry::class)->getEntries($filters);

        // load pagination limit parameter from config
        $limit = ($this->config->get("entries_list_pagination_limit") > 0)
            ? $this->config->get("entries_list_pagination_limit")
            : 10;

        // pagination
        $entries = $this->paginator->paginate(
            $entries,
            $page,
            $limit
        );

        /** @var SlidingPagination $entries */
        return $entries;
    }

    /**
     * Get categories for current page language
     *
     * @param  string   $language Page language
     * @param  array    $filters Custom filters
     * @return array    $entryCategories    Entry categories list
     */
    public function getCategories(string $language = "en", array $filters = []): array
    {
        $entryCategories = $this->em->getRepository(EntryCategory::class)->findBy(
            [
                'language' => $language,
                'enabled' => true
            ],
            ['name' => 'ASC']
        );

        return $entryCategories;
    }

    /**
     * Get previous and next entries for a given entry
     *
     * @param  Entry $entry Entry entity
     * @param  String $language Entry language
     * @return array    $arrayResult    Query results
     */
    public function getPreviousAndNextEntries(Entry $entry, string $language = "en"): array
    {
        $arrayResult = [
            'nextEntry' => [],
            'previousEntry' => []
        ];

        $result = $this->em->getRepository(Entry::class)->getPreviousAndNextEntries($entry, $language);

        if ($result) {
            $entryId = $entry->getId();

            foreach ($result as $entryRow) {
                if ($entryId > $entryRow['id']) {
                    $arrayResult['nextEntry'] = $entryRow;
                } else if ($entryId < $entryRow['id']) {
                    $arrayResult['previousEntry'] = $entryRow;
                }
            }
        }

        return $arrayResult;
    }
}
