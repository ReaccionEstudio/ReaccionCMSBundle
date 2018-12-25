<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;
use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
	 * Check if username and email already exists
	 * 
	 * @param 	String 	$username 	Username
	 * @param 	String 	$email 		Email
	 * @return 	Array   [type] 	    Found user data
	 */
    public function userExists(String $username, String $email)
    {
    	$em = $this->getEntityManager();

    	$dql = "SELECT COUNT(u.id) AS total, u.usernameCanonical, u.emailCanonical 
                FROM App\ReaccionEstudio\ReaccionCMSBundle\Entity\User u 
                WHERE u.usernameCanonical = :username 
                OR  u.emailCanonical = :email";

        $query = $em->createQuery($dql)
        			 ->setParameter('username', $username)
        			 ->setParameter('email', $email);

    	return $query->getSingleResult();
    }
}
