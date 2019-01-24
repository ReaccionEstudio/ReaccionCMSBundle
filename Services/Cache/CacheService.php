<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Cache;

	use Symfony\Component\Cache\Adapter\ApcuAdapter;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Cache\CacheServiceInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

	/**
	 * Cache service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class CacheService implements CacheServiceInterface
	{
		/**
		 * @var ApcuAdapter
		 *
		 * APCu adapter
		 */
		private $cache;

		/**
		 * @var LoggerServiceInterface
		 *
		 * Logger service
		 */
		private $logger;

		/**
		 * Constructor
		 */
		public function __construct(LoggerServiceInterface $logger)
		{
			$this->cache = new ApcuAdapter('', 0);
			$this->logger = $logger;
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
				$this->logger->logException($e, 'Error setting cache item.');
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

		/**
		 * Search cache item by key
		 * 
		 * @param 	String 		$query 		Search query
		 * @return 	Array 		$results 	Search results
		 */
		public function searchKey(String $query = "") : Array
		{
			if( ! strlen($query) ) return [];

			$iter 	 = new \APCIterator('user');
			$results = [];

			foreach ($iter as $item) 
			{
				if(preg_match("/" . $query . "/", $item['key']))
				{
					$results[] = $item['value'];
				}
			}

			return $results;
		}

		/**
		 * Check if item exists in cache storage
		 * 
		 * @param 	String 	$key 	Cache item key
		 * @return 	Array 	[type] 	Cache item value
		 */
		public function hasItem(String $key)
		{
			return $this->cache->hasItem($key);
		}

		/**
		 * Remove item from cache storage
		 *
		 * @param 	String 		$key 		Cache item key
		 * @return 	Boolean		true|false 	Operation result
		 */
		public function remove(String $key) : bool
		{
			if($this->cache->hasItem($key))
			{
				return $this->cache->deleteItem($key);
			}
			
			return false;
		}
	}