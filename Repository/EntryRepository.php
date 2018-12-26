<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Repository;

use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EntryRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    /**
     * Get entry entities
     *
     * @param 	Array 	$filters 	Query filters
     * @return 	Array 	[type]		Query result
     */
    public function getEntries(Array $filters = [])
    {
    	$defaultFilters = ['language' => 'en'];
    	$filters = array_merge($defaultFilters, $filters);

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
}