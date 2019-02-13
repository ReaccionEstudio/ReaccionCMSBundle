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
    	$result = $query->getOneOrNullResult();

    	return ($result['total']) ?? 0;
	}

	/**
	 * Get first main page slug
	 *
	 * @return String 	[type] 	Main page slug
	 */
	public function getFirstMainPageSlug()
	{
		$em = $this->getEntityManager();

    	$dql = "SELECT p.slug 
                FROM ReaccionEstudio\ReaccionCMSBundle\Entity\Page p 
                WHERE p.isEnabled = 1 
                AND p.mainPage = 1
                ORDER BY p.id ASC 
                ";

        $query  = $em->createQuery($dql)->setMaxResults(1);
    	$result = $query->getOneOrNullResult();
    	return ($result['slug']) ?? null;
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