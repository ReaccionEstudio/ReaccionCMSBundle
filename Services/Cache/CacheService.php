<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Cache;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Cache\Adapter\ApcuAdapter;

	/**
	 * Cache service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class CacheService
	{
		/**
		 * @var ApcuAdapter
		 *
		 * APCu adapter
		 */
		private $cache;

		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->cache = new ApcuAdapter();
		}

		/**
		 * Set parameter in cache
		 *
		 * @param 	String 		$key 		Cache key
		 * @return 	Boolean		true|false  
		 */
		public function set(String $key, $value) : bool
		{
			try
			{
				$cachedItem = $this->cache->getItem($key);

				// Save config value in cache
				$cachedItem->set($value);
				$this->cache->save($cachedItem);

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
		 * Get parameter from cache
		 *
		 * @param 	String 		$key 		Cache parameter key
		 * @return 	Any			[type]		Cache parameter value
		 */
		public function get(String $key)
		{
			$cachedItem = $this->cache->getItem($key);

			if($cachedItem->isHit())
			{
				// key is cached
				return $cachedItem->get();
			}

			return null;
		}

		public function remove(String $key)
		{
			
		}
	}