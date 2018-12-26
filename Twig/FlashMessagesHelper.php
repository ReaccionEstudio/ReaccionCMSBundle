<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

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
     * @param  Array    $keys       Flash messages keys to display
     * @return String   $result     Flash messages in HTML
     */
    public function displayFlashMessages(Array $keys=[]) : String
    {
        $flashMessages = $this->session->getFlashBag()->all();

        if(empty($flashMessages)) return '';

        $success_num = 0;
        $error_num   = 0;
        $success_div = '<div class="alert alert-success">';
        $error_div   = '<div class="alert alert-danger">';
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
                $error_div .= "<p>" . $message . "</p>";
                $error_num++;
            }
        }

        $success_div .= '</div>';
        $error_div .= '</div>';

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