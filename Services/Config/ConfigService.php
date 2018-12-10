<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Config;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Cache\Adapter\ApcuAdapter;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;

	/**
	 * Config service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class ConfigService
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
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em)
		{
			$this->em = $em;
			$this->cache = new ApcuAdapter();
		}

		/**
		 * Get configuration value
		 *
		 * @param String 	$key 	Configuration entity name
		 * @param String 	[type] 	Configuration entity value
		 */
		public function get(String $key) :  String
		{
			$cacheKey 	= "config." . $key;
			$cachedItem = $this->cache->getItem($cacheKey);

			if($cachedItem->isHit())
			{
				// key is cached
				$keyValue = $cachedItem->get();
			}
			else
			{
				// generate cache
				$keyValue = $this->getConfigFromDatabase($key);

				if(strlen($keyValue))
				{
					// Save config value in cache
					$cachedItem->set($keyValue);
					$this->cache->save($cachedItem);
				}
			}

			return $keyValue;
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