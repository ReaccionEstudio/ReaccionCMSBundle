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
}