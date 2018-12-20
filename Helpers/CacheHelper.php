<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Helpers;

	final class CacheHelper
	{
		/**
		 * Convert slug string to a valid cache key name
		 *
		 * @param 	String 	$slug 		Slug to convert
		 * @param  	String 	$suffix 	Suffix for cache key
		 * @return  String 	$cacheKey 	Generated cache key
		 */
		public function convertSlugToCacheKey(String $slug, String $suffix = "" ) : String
		{
			return str_replace("-", "_", $slug) . $suffix;
		}
	}