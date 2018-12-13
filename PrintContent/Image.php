<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\PrintContent;

	use Symfony\Component\Routing\RouterInterface;
	use Symfony\Component\DependencyInjection\ContainerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\PrintContent\PrintContentInterface;

	class Image implements PrintContentInterface
	{
		/**
		 * Constructor
		 *
		 * @param String  $contentValue  Content value
		 * @param Array   $properties  	 Image properties
		 */
		public function __construct(RouterInterface $router, String $contentValue, Array $properties = [] )
		{
			$this->router 		= $router;
			$this->contentValue = $contentValue;
		}

		/**
		 * Return content value
		 *
		 * @return String 	$imageHtmlElement    Image HTML element
		 */
		public function getValue() : String
		{
			// TODO: create convertArrayToAttributesString() method in new class
	        $imageUrl = $this->getAppUrl() . '/uploads/' . $this->contentValue;
	        $imageHtmlElement = '<img src="' . $imageUrl . '" alt="' . basename($this->contentValue) . '" />';

			return $imageHtmlElement;
		}

		/**
		 * Get app base url
		 *
		 * @return String 	$appUrl 	App base url
		 */
		private function getAppUrl() : String
		{
			$context = $this->router->getContext();
			$port 	 = $context->getHttpPort();
	        $appUrl  = $context->getScheme() . '://' . $context->getHost();

			if($port != "8080")
			{
				$appUrl .= ":" . $port;
			}

			return $appUrl;
		}
	}