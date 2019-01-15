<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageTranslationGroup;

class PageTranslationGroupRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PageTranslationGroup::class);
    }
}