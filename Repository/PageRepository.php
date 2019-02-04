<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;

class PageRepository extends EntityRepository
{
	/**
	 * Get total Page entities
	 *
	 * @return Interger 	[type] 		Total existing page entities
	 */
	public function getTotalPages() : Int
	{
		$em = $this->getEntityManager();

    	$dql = "SELECT COUNT(p.id) AS total 
                FROM ReaccionEstudio\ReaccionCMSBundle\Entity\Page p 
                WHERE p.isEnabled = 1";

        $query  = $em->createQuery($dql);
    	$result = $query->getSingleResult();

    	return ($result['total']) ?? 0;
	}

	/**
	 * Get all Page entities
	 *
	 * @param 	Array 		$params 		Query filter parameters
	 * @return 	Array 		[type]			Array page entities
	 */
	public function getPages(Array $params = ['language' => 'en', 'isEnabled' => true]) : Array
	{
		$em = $this->getEntityManager();
		return $em->getRepository(Page::class)->findBy($params, ['id' => 'ASC']);
	}
}