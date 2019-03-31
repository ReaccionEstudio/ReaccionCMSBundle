<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Model;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractModel
{
    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class): void
    {
        $this->class = $class;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @param EntityManagerInterface $em
     */
    public function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }
    protected $class;

    /**
     * @var EntityManagerInterface
     *
     * EntityManager
     */
    protected $em;

    /**
     * AbstractModel constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create() : Language
    {
        return new $this->class();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->getRepo()->findOneBy(['id' => $id]);
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        return $this->getRepo()->findAll();
    }

    /**
     * @param $entity
     * @throws \Exception
     */
    public function save($entity)
    {
        try
        {
            $this->em->persist($entity);
            $this->em->flush();
        }
        catch(\Exception $e)
        {
            // TODO: log error
            throw $e;
        }
    }

    /**
     * @param $entity
     * @throws \Exception
     */
    public function remove($entity)
    {
        try
        {
            $this->em->remove($entity);
            $this->em->flush();
        }
        catch(\Exception $e)
        {
            // TODO: log error
            throw $e;
        }
    }

    /**
     * @return mixed
     */
    public function getRepo()
    {
        return $this->em->getRepository($this->class);
    }
}