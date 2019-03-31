<?php
namespace ReaccionEstudio\ReaccionCMSBundle\Manager;

use ReaccionEstudio\ReaccionCMSBundle\Entity\Language;
use ReaccionEstudio\ReaccionCMSBundle\Constants\Configuration;
use ReaccionEstudio\ReaccionCMSBundle\Manager\AbstractManager;

/**
 * Class LanguageManager
 * @package ReaccionEstudio\ReaccionCMSBundle\Manager
 */
class LanguageManager extends AbstractManager
{
    /**
     * @var string
     */
    protected $class = Language::class;

    /**
     * @param $entity
     * @return bool
     */
    public function save($entity) : bool
    {
        try
        {
            // check if main field needs to be resetted in all entities
            if($entity->getMain())
            {
                $this->resetMainFieldInAllEntities();
            }

            $this->em->persist($entity);
            $this->em->flush();

            return true;
        }
        catch(\Exception $e)
        {
            // TODO: log error
            throw $e;
            return false;
        }
    }

    /**
     * @param String $value
     * @return bool
     */
    public function updateConfigParam(String $value) : bool
    {
        return $this->configService->set(Configuration::DEFAULT_LANGUAGE_PARAM, $value);
    }

    /**
     * @return mixed
     */
    public function resetMainFieldInAllEntities()
    {
        return $this->getRepo()->resetMainFieldInAllEntities();
    }
}