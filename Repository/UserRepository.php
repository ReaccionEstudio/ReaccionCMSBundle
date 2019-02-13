<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ReaccionEstudio\ReaccionCMSBundle\Entity\User;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository
{
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
                FROM ReaccionEstudio\ReaccionCMSBundle\Entity\User u 
                WHERE u.usernameCanonical = :username 
                OR  u.emailCanonical = :email";

        $query = $em->createQuery($dql)
        			 ->setParameter('username', $username)
        			 ->setParameter('email', $email);

    	return $query->getOneOrNullResult();
    }

    /**
     * Get admin email addresses
     *
     * @return  Array   [type]      Admin emails
     */
    public function getAdminEmailAdresses()
    {
        $em = $this->getEntityManager();

        $dql = "SELECT 
                u.username, 
                u.nickname, 
                u.email 
                FROM ReaccionEstudio\ReaccionCMSBundle\Entity\User u 
                WHERE u.enabled = 1 
                AND u.roles LIKE '%\"ROLE_ADMIN\"%'";

        $query = $em->createQuery($dql);

        return $query->getResult();
    }

    /**
     * Get admin user entities
     *
     * @return  Array   [type]      Admin user entities
     */
    public function getAdminEntities()
    {
        $em = $this->getEntityManager();

        $dql = "SELECT 
                u
                FROM ReaccionEstudio\ReaccionCMSBundle\Entity\User u 
                WHERE u.enabled = 1 
                AND u.roles LIKE '%\"ROLE_ADMIN\"%'";

        $query = $em->createQuery($dql);

        return $query->getResult();
    }
}
