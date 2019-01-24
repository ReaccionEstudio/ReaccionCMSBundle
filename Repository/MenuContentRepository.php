<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Repository;

use App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MenuContentRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MenuContent::class);
    }

    /**
     * Checks if given page has a relationship with any Menu
     *
     * @param  Integer 			$pageId 	Page Id
     * @return Menu|null		[type] 		Menu entity
     */
    public function getPageMenu(Int $pageId)
    {
    	$em = $this->getEntityManager();

    	$dql = "SELECT 
    			me 
                FROM App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent m 
                LEFT JOIN App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu me 
                WITH me.id = m.menu
                WHERE m.type = 'page' 
                AND m.value = :pageId
                AND m.enabled = 1";

        $query  = $em->createQuery($dql)->setParameter('pageId', $pageId);

    	return $query->getSingleResult();
    }
}