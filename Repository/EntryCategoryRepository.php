<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Repository;

use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EntryCategoryRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntryCategory::class);
    }
}