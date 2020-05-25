<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\Config;

use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

/**
 * Config service.
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ConfigService implements ConfigServiceInterface
{
    /**
     * @var EntityManagerInterface
     *
     * EntityManager
     */
    private $em;

    /**
     * @var LoggerServiceInterface
     *
     * Logger
     */
    private $logger;

    /**
     * Constructor
     */
    public function __construct(EntityManagerInterface $em, LoggerServiceInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Get configuration value
     *
     * @param string $key
     * @return bool|string
     */
    public function get(string $key)
    {
        // generate cache
        $config = $this->getConfigFromDatabase($key);

        if(empty($config)) {
            throw new \Exception(sprintf('Config parameter "%s" was not found', $key));
        }

        $keyValue = [
            'type' => $config->getType(),
            'value' => $config->getValue()
        ];

        return $this->getConfigValueWithType($keyValue);
    }

    /**
     * Update configuration parameter value
     *
     * @param  string $key Configuration entity name
     * @param  mixed        [type]    Parameter value
     * @return boolean    true|false    Update result
     */
    public function set(string $key, $value)
    {
        // get config entity
        $configEntity = $this->em->getRepository(Configuration::class)->findOneBy(['name' => $key]);

        if (empty($configEntity)) {
            $this->logger->addError("Error updating configuration param value: Config key '" . $key . "' was not found.");
            return false;
        }

        try {
            $configEntity->setValue($value);

            $this->em->persist($configEntity);
            $this->em->flush();

            $this->logger->addInfo("Config key '" . $key . "' value updated.");

            return true;
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->logger->logException($e, "Error in ConfigService::set() method.");
            return false;
        }
    }

    /**
     * Get configuration entity
     *
     * @param  String $key Configuration entity name
     * @return Configuration    [type]            Configuration entity
     */
    public function getEntity(String $key): Configuration
    {
        try {
            return $this->em->getRepository(Configuration::class)->findOneBy(['name' => $key]);
        } catch (\Exception $e) {
            // TODO: log error
            return new Configuration();
        }
    }

    /**
     * Get config parameter from database
     *
     * @param String $key Configuration entity name
     * @param String    [type]    Configuration entity value
     */
    private function getConfigFromDatabase(String $key)
    {
        if (empty($key)) return null;

        try {
            return $this->em->getRepository(Configuration::class)->findOneBy(['name' => $key]);
        } catch (\Exception $e) {
            // TODO: log error
            return null;
        }
    }

    /**
     * @param Configuration $config
     * @return bool|string
     */
    private function getConfigValueWithType(array $config)
    {
        if ($config['type'] == 'boolean') {
            return (bool)$config['value'];
        }

        return $config['value'];
    }
}
