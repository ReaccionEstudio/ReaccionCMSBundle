<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Helpers;

	final class CacheHelper
	{
		/**
		 * Convert slug string to a valid cache key name
		 *
		 * @param 	String 	$slug 		Slug to convert
		 * @param  	String 	$suffix 	Suffix for cache key
		 * @return  String 	[type] 		Generated cache key (MD5)
		 */
		public function convertSlugToCacheKey(String $slug, String $suffix = "" ) : String
		{
			return md5($slug . $suffix);
		}
	}