<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;

class MenuContentRepository extends EntityRepository
{
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
                FROM ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent m 
                LEFT JOIN ReaccionEstudio\ReaccionCMSBundle\Entity\Menu me 
                WITH me.id = m.menu
                WHERE m.type = 'page' 
                AND m.value = :pageId
                AND m.enabled = 1";

        $query  = $em->createQuery($dql)->setParameter('pageId', $pageId);

    	return $query->getOneOrNullResult();
    }
}