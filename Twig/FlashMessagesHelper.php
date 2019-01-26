<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Twig;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * FlashMessagesHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class FlashMessagesHelper extends \Twig_Extension
{
    /**
     * Constructor
     *
     * @param SessionInterface     $session     Session service
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('displayFlashMessages', array($this, 'displayFlashMessages'))
        );
    }

    /**
     * Display flash messages
     *
     * @param  Array    $keys           Flash messages keys to display
     * @param  Array    $extraClasses   Extra classes
     * @return String   $result         Flash messages in HTML
     */
    public function displayFlashMessages(Array $keys=[], Array $extraClasses = []) : String
    {
        $flashMessages = $this->session->getFlashBag()->all();

        if(empty($flashMessages)) return '';

        if( ! in_array("fixed-alert", $extraClasses))
        {
            $extraClasses[] = "show";
        }

        $extraClassesString = ( ! empty($extraClasses) ) ? implode(" ", $extraClasses) : "";

        $success_num = 0;
        $error_num   = 0;
        $success_div = '<div class="alert alert-success alert-dismissible fade ' . $extraClassesString . '">';
        $error_div   = '<div class="alert alert-danger alert-dismissible fade ' . $extraClassesString . '">';
        $result      = '';

        foreach($flashMessages as $key => $message)
        {
            if( ! empty($keys) && ! in_array($key, $keys)) continue;

            $message = $message[0];

            if(preg_match("/success/", $key))
            {
                $success_div .= '<p class="mb-0">' . $message . '</p>';
                $success_num++;
            }
            else if(preg_match("/error/", $key))
            {
                $error_div .= '<p class="mb-0">' . $message . "</p>";
                $error_num++;
            }
        }

        $success_div .= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button></div>';
        $error_div .= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button></div>';

        if($error_num > 0)
        {
            $result .= $error_div;
        }

        if($success_num > 0)
        {
            $result .= $success_div;
        }
        
        return $result;
    }

	public function getName()
    {
        return 'ConfigHelper';
    }
}