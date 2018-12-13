<?php

    namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

    use Services\Managers\ManagerPermissions;
    use Symfony\Component\Routing\RouterInterface;

    use App\ReaccionEstudio\ReaccionCMSBundle\PrintContent\Image;
    use App\ReaccionEstudio\ReaccionCMSBundle\PrintContent\HtmlText;

    /**
     * ContentHelper class (Twig_Extension)
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
    class ContentHelper extends \Twig_Extension
    {
        public function __construct(RouterInterface $router)
        {
            $this->router = $router;
        }

    	public function getFunctions()
        {
            return array(
                new \Twig_SimpleFunction('printContent', array($this, 'printContent'))
            );
        }

        /**
         * Print content
         *
         * @param   Array    $viewContent           View page content object
         * @param   Array    $contentKey            Content key
         * @param   Array    $contentProperties     Define content properties
         * @return  String   $htmlContent           Content as HTML
         */
        public function printContent(Array $viewContent, String $contentKey, Array $contentProperties=[]) : String
        {
            if( ! isset($viewContent[$contentKey])) return '';

            $htmlContent    = "";
            $content        = $viewContent[$contentKey];
            $contentValue   = $content['value'];

            switch($content['type'])
            {
                case 'text_html':
                    $htmlContent = (new HtmlText($contentValue))->getValue();
                break;

                case 'img':
                case 'image':
                    $htmlContent = (new Image($this->router, $contentValue))->getValue();
                break;
            }

            return $htmlContent;
        }

    	public function getName() : String
        {
            return 'ContentHelper';
        }
    }