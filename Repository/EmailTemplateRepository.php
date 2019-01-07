<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Repository;

use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EmailTemplateRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EmailTemplate::class);
    }
}