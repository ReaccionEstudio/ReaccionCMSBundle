<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Config;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Cache\Adapter\ApcuAdapter;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

	/**
	 * Config service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class ConfigService implements ConfigServiceInterface
	{
		/**
		 * @var ApcuAdapter
		 *
		 * APCu adapter
		 */
		private $cache;

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
			$this->em 		= $em;
			$this->logger 	= $logger;
			$this->cache 	= new ApcuAdapter();
		}

		/**
		 * Get configuration value
		 *
		 * @param String 	$key 			Configuration entity name
		 * @param String 	$loadFromCache 	Indicates if the config entity has to be loaded from cache
		 * @param String 	[type] 			Configuration entity value
		 */
		public function get(String $key, Bool $loadFromCache = true) :  String
		{
			$cacheKey 	= $this->getCacheKey($key);
			$cachedItem = $this->cache->getItem($cacheKey);

			if($loadFromCache && $cachedItem->isHit())
			{
				// key is cached
				$keyValue = $cachedItem->get();
			}
			else
			{
				// generate cache
				$keyValue = $this->getConfigFromDatabase($key);

				if(strlen($keyValue) && $loadFromCache)
				{
					// Save config value in cache
					$cachedItem->set($keyValue);
					$this->cache->save($cachedItem);
				}
			}

			return $keyValue;
		}

		/**
		 * Update configuration parameter value
		 *
		 * @param  String 	$key 		Configuration entity name
		 * @param  Any 		[type]  	Parameter value
		 * @return Boolean 	true|false 	Update result
		 */
		public function set(String $key, $value)
		{
			// get config entity
			$configEntity = $this->em->getRepository(Configuration::class)->findOneBy(['name' => $key]);

			if(empty($configEntity))
			{
				$this->logger->addError("Error updating configuration param value: Config key '" . $key . "' was not found.");
				return false;
			}

			try
			{
				$configEntity->setValue($value);
				
				$this->em->persist($configEntity);
				$this->em->flush();

				$this->logger->addInfo("Config key '" . $key . "' value updated.");

				// update cache
				$this->updateConfigCacheValue($key, $value);

				return true;
			}
			catch(\Doctrine\DBAL\DBALException $e)
			{
				$this->logger->logException($e, "Error in ConfigService::set() method.");
				return false;
			}
		}

		/**
		 * Update config cache value if param is already in the cache storage
		 *
		 * @param  String 	$key 		Configuration entity name
		 * @param  Any 		[type]  	Parameter value
		 * @return Boolean 	true|false 	Update result
		 */
		private function updateConfigCacheValue(String $key, $value) : Bool
		{
			$cacheKey = $this->getCacheKey($key);

			try
			{
				$cachedItem = $this->cache->getItem($cacheKey);

				if( ! $cachedItem->isHit()) return true;

				// Save config value in cache
				$cachedItem->set($value);
				$this->cache->save($cachedItem);

				return true;
			}
			catch(\Exception $e)
			{
				$this->logger->logException($e, "Error in ConfigService::updateConfigCacheValue() method.");
				return false;
			}
		}

		/**
		 * Get cache key name for config parameter keys
		 *
		 * @param String 	$key 	Configuration entity name
		 * @return String 	[type] 	Configuration cache key
		 */
		private function getCacheKey(String $key)
		{
			return "config." . $key;
		}

		/**
		 * Get configuration entity
		 *
		 * @param  String 			$key 			Configuration entity name
		 * @return Configuration 	[type] 			Configuration entity
		 */
		public function getEntity(String $key) : Configuration
		{
			try
			{
				return $this->em->getRepository(Configuration::class)->findOneBy([ 'name' => $key ]);
			}
			catch(\Exception $e)
			{
				// TODO: log error
				return new Configuration();
			}
		}

		/**
		 * Get config parameter from database
		 *
		 * @param String 	$key 	Configuration entity name
		 * @param String 	[type] 	Configuration entity value
		 */
		private function getConfigFromDatabase(String $key) : String
		{
			if(empty($key)) return '';

			try
			{
				$config = $this->em->getRepository(Configuration::class)->findOneBy([ 'name' => $key ]);
				return ( ! empty($config) ) ? $config->getValue() : '';
			}
			catch(\Exception $e)
			{
				// TODO: log error
				return '';
			}
		}
	}