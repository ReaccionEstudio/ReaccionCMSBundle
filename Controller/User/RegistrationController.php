<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ReaccionEstudio\ReaccionCMSBundle\Constants\Cookies;
use ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserSettingsType;
use ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserRegisterType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Construct
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Register a new user
     */
    public function index(Request $request)
    {
        $configService = $this->get("reaccion_cms.config");
        $isRegistrarionEnabled = $configService->get("user_registration");

        if ($isRegistrarionEnabled === false) {
            throw new AccessDeniedException();
        }

        $sitename = $configService->get("site_name");
        $seo = [
            'title' => $this->translator->trans("user_register.seo_title", ['%sitename%' => $sitename])
        ];

        // form
        $form = $this->createForm(UserRegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form['username']->getData();
            $email = $form['email']->getData();
            $password = $form['password']->getData();

            // create new user
            $newUser = $this->get("reaccion_cms.user")->createNewUserIfAvailable($username, $email, $password);

            if ($newUser === true) {
                // sign in new user
                $this->get("reaccion_cms.authentication")->setUser($newUser)->authenticate(true);

                // redirect to home page
                return $this->redirectToRoute("index");
            }
        }

        // view
        $view = $this->get("reaccion_cms.theme")->getConfigView("register", true);
        $vars = [
            'form' => $form->createView(),
            'seo' => $seo
        ];

        return $this->render($view, $vars);
    }
}
