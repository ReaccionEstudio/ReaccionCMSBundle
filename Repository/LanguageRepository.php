<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Language;

class LanguageRepository extends EntityRepository
{
    public function resetMainFieldInAllEntities()
    {
        $em = $this->getEntityManager();

        $dql = "UPDATE ReaccionEstudio\ReaccionCMSBundle\Entity\Language l 
                SET l.main = NULL
                WHERE l.main = 1";

        $query = $em->createQuery($dql);
        return $query->execute();
    }
}