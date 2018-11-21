<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Repository;

use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MenuRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Menu::class);
    }
}