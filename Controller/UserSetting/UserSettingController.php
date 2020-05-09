<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Controller\UserSetting;

use ReaccionEstudio\ReaccionCMSBundle\Form\UserSetting\UserProfileType;
use ReaccionEstudio\ReaccionCMSBundle\Form\UserSetting\UserSettingsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserSettingController
 * @package ReaccionEstudio\ReaccionCMSBundle\Controller\UserSetting
 */
class UserSettingController extends AbstractController
{
    public function index()
    {
        if(empty($this->getUser())) {
            $this->createAccessDeniedException();
        }

        $profileForm = $this->createForm(UserProfileType::class, $this->getUser());
        $settingsForm = $this->createForm(UserSettingsType::class, $this->getUser());

        $view = $this->get("reaccion_cms.theme")->getConfigView('user_settings', true);
        $vars = [
            'seo' => [
                'title' => 'Profile and account settings'
            ],
            'profileForm' => $profileForm->createView(),
            'settingsForm' => $settingsForm->createView()
        ];

        return $this->render($view, $vars);
    }
}
