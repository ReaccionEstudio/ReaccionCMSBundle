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
}