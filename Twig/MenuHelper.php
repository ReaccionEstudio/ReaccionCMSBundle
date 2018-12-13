<?php

    namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

    use Services\Managers\ManagerPermissions;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

    /**
     * MenuHelper class (Twig_Extension)
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
    class MenuHelper extends \Twig_Extension
    {
        /**
         * @var UrlGeneratorInterface
         *
         * UrlGeneratorInterface
         */
        private $generator;

        /**
         * Constructor
         *
         * @param Router     $router     Symfony router
         */
        public function __construct(UrlGeneratorInterface $generator)
        {
            $this->generator = $generator;
        }

    	public function getFunctions() : Array
        {
            return array(
                new \Twig_SimpleFunction('getMenuLinkAttrs', array($this, 'getMenuLinkAttrs'))
            );
        }

        /**
         * Get <a> HTML element attributes for menu links
         *
         * @param   Array    $menuItem        Array with menu item data
         * @return  String   $stringAttrs     Attributes as string
         */
        public function getMenuLinkAttrs(Array $menuItem) : String
        {
            $stringAttrs = '';
            $attrs = ['href' => '#'];

            if($menuItem['type'] == "page" )
            {
                $url = $this->generator->generate("index_slug", [ 'slug' => $menuItem['value'] ]);
                $attrs['data-slug'] = $menuItem['value'];
            }
            elseif($menuItem['type'] == "url")
            {
                $url = $menuItem['value'];
            }

            if( ! empty($url))
            {
                $attrs['href'] = $url;
            }

            if( isset($menuItem['target']))
            {
                $attrs['target'] =  $menuItem['target'];
            }

            if( isset($menuItem['name']))
            {
                $attrs['title'] = $menuItem['name'];
            }

            foreach($attrs as $attr => $value)
            {
                $stringAttrs .= ' ' . $attr . '="' . $value . '"';
            }

            return $stringAttrs;
        }

    	public function getName()
        {
            return 'MenuHelper';
        }
    }