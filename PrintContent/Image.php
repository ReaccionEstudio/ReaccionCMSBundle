<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\PrintContent;

	use Symfony\Component\Routing\RouterInterface;
	use Symfony\Component\DependencyInjection\ContainerInterface;
	use ReaccionEstudio\ReaccionCMSBundle\PrintContent\PrintContentInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Helpers\HtmlAttributesHelper;

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
			$this->properties  	= $properties;
			$this->attrs  		= [];
		}

		/**
		 * Return content value
		 *
		 * @return String 	$imageHtmlElement    Image HTML element
		 */
		public function getValue() : String
		{
			// get full image url
	        $imageUrl = $this->getAppUrl() . '/uploads/' . $this->contentValue;

	        // create image attributes
	        $this->attrs['src'] = $imageUrl;
	        $this->attrs['alt'] = basename($this->contentValue);

	        // get attributes as string
            $stringAttrs = (new HtmlAttributesHelper($this->attrs))->getAttributesAsString();

            // generate HTML img element
	        $imageHtmlElement = '<img ' . $stringAttrs . ' />';

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