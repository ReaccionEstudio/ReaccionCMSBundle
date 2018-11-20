<?php

    namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

    use Services\Managers\ManagerPermissions;
    use App\ReaccionEstudio\ReaccionCMSBundle\PrintContent\HtmlText;

    /**
     * ContentHelper class (Twig_Extension)
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
    class ContentHelper extends \Twig_Extension
    {
    	public function getFunctions()
        {
            return array(
                new \Twig_SimpleFunction('printContent', array($this, 'printContent'))
            );
        }

        /**
         * Print content
         *
         * @param   Array    $viewContent     View page content object
         * @return  String   $htmlContent     Content as HTML
         */
        public function printContent(Array $viewContent) : String
        {
            $htmlContent  = "";
            $contentValue = $viewContent['value'];

            switch($viewContent['type'])
            {
                case 'text_html':
                    $htmlContent = (new HtmlText($contentValue))->getValue();
                break;

                case 'image':
                    // TODO ...
                break;
            }

            return $htmlContent;
        }

    	public function getName()
        {
            return 'ContentHelper';
        }
    }