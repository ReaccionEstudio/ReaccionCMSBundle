<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;

class EntryRepository extends EntityRepository
{
    /**
     * Get entry entities
     *
     * @param 	array 	$filters 	Query filters
     * @return 	array 	[type]		Query result
     */
    public function getEntries(array $filters = [])
    {
        if(!isset($filters['language'])) {
            $filters['language'] = 'en';
        }

    	// query builder
    	$qb = $this->createQueryBuilder('e')
    				->where('e.enabled = 1')
    				->andWhere('e.language = :language');

        if(isset($filters['categories']))
        {
            $qb->innerJoin('e.categories', 'c')
               ->andWhere('c.slug IN (:categorySlugs)')
               ->setParameter('categorySlugs', $filters['categories']);
        }

    	if(isset($filters['tag']))
    	{
    		$qb->andWhere('e.tags LIKE :tag')
           	   ->setParameter('tag', '%' . $filters['tag'] . '%');
    	}

    	$qb->orderBy('e.id', 'DESC');
    	$qb->setParameter('language', $filters['language']);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get previous and next entries for a given entry
     *
     * @param  Entry    $entry          Entry entity
     * @param  String   $language       Entry language
     * @return Array    [type]          Query result
     */
    public function getPreviousAndNextEntries(Entry $entry, String $language="en")
    {
        $em = $this->getEntityManager();
        $entryId = $entry->getId();

        $dql = "SELECT 
                e.id, e.name, e.slug
                FROM ReaccionEstudio\ReaccionCMSBundle\Entity\Entry e 
                WHERE 
                e.id  = (SELECT MIN(e1.id) FROM ReaccionEstudio\ReaccionCMSBundle\Entity\Entry e1 WHERE e1.id > :entryId AND e1.language = :language AND e1.enabled = 1) 
                OR e.id  = (SELECT MAX(e2.id) FROM ReaccionEstudio\ReaccionCMSBundle\Entity\Entry e2 WHERE e2.id < :entryId AND e2.language = :language AND e2.enabled = 1)
                ";

        $query = $em->createQuery($dql)
                    ->setParameter('language', $language)
                    ->setParameter('entryId', $entryId)
                    ;

        return $query->getArrayResult();
    }
}
