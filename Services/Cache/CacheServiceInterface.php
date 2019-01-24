<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Cache;

	interface CacheServiceInterface
	{
		/**
		 * Set parameter in cache
		 *
		 * @param 	String 		$key 		Cache key
		 * @return 	Boolean		true|false  
		 */
		public function set(String $key, $value);
		

		/**
		 * Get parameter from cache
		 *
		 * @param 	String 		$key 		Cache parameter key
		 * @return 	Any			[type]		Cache parameter value
		 */
		public function get(String $key);

		/**
		 * Check if item exists in cache storage
		 * 
		 * @param 	String 	$key 	Cache item key
		 * @return 	Array 	[type] 	Cache item value
		 */
		public function hasItem(String $key);

		/**
		 * Remove item from cache storage
		 *
		 * @param 	String 		$key 		Cache item key
		 * @return 	Boolean		true|false 	Operation result
		 */
		public function remove(String $key);
	}