<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\User;

use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use ReaccionEstudio\ReaccionCMSBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * User service
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class UserService
{
    /**
     * @var EntityManagerInterface
     *
     * EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     *
     * TranslatorInterface
     */
    private $translator;

    /**
     * @var Session
     *
     * Session
     */
    private $session;

    /**
     * @var Symfony\Bundle\FrameworkBundle\Routing\Router
     *
     * Router service
     */
    private Router $router;

    private Request $request;

    /**
     * Constructor
     */
    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        SessionInterface $session,
        Router $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->em = $em;
        $this->session = $session;
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * Check if username and email is available
     *
     * @param String $username Username
     * @param String $email Email
     * @return    Boolean true|false  Signup process result
     */
    public function createNewUserIfAvailable(string $username, string $email, string $password)
    {
        $userExists = $this->em->getRepository(User::class)->userExists($username, $email, $password);

        if ($userExists['total'] > 0) {
            if ($username == $userExists['usernameCanonical']) {
                $this->session->getFlashBag()->add('signup_errors', $this->translator->trans('signup.username_exists'));
            }

            if ($email == $userExists['emailCanonical']) {
                $this->session->getFlashBag()->add('signup_errors', $this->translator->trans('signup.email_exists'));
            }

            return false;
        } else if ($userExists['total'] == 0) {
            $this->createNewUser($username, $email, $password);
            return true;
        }

        return false;
    }

    /**
     * Create new user
     *
     * @param String $username Username
     * @param String $email Email address
     * @param String $password Password
     * @return User    $user        New user entity
     */
    public function createNewUser(string $username, string $email, string $password)
    {
        try {
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPlainPassword($password);
            $user->setEnabled(true);

            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            // TODO: log error
            $this->session->getFlashBag()->add('signup_errors', $e->getMessage());
        }

        return $user;
    }

    /**
     * Redirect user depending on the event
     *
     * @return RedirectResponse     [type]  Redirection response
     */
    public function redirect(string $event = ''): RedirectResponse
    {
        $userRedirectionEvent = new UserRedirectionEvent($event, $this->router, $this->request);
        return $userRedirectionEvent->redirect();
    }
}
