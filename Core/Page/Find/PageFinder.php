<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Page\Find;

use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Adapters\PageViewAdapter;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Composer\PageComposer;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions\NotFoundRouteException;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page as PageEntity;

/**
 * Class PageFinder
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Page\Find
 */
final class PageFinder
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * PageFinder constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $id
     */
    public function find(int $id) : array
    {
        return $this->_find(['id' => $id], $id);
    }

    /**
     * @param string $slug
     */
    public function findBySlug(string $slug) : array
    {
        return $this->_find(['slug' => $slug], $slug);
    }

    /**
     * @param array $filter
     * @return \ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Page
     * @throws NotFoundRouteException
     * @throws \ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions\NotFoundRouteDataException
     */
    private function _find(array $filter, string $value) : array
    {
        $pageEntity = $this->em->getRepository(PageEntity::class)->findOneBy($filter);

        if(empty($pageEntity)){
            throw new NotFoundRouteException($value);
        }

        $pageComposer = new PageComposer($pageEntity);
        return $pageComposer->compose();
    }
}
