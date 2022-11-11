<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\User;

use ReaccionEstudio\ReaccionCMSBundle\Common\Constants\UserRedirections;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use ReaccionEstudio\ReaccionCMSBundle\Entity\User;

/**
 * User redirection event
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
final class UserRedirectionEvent
{
    /**
     * @var String
     *
     * Homepage url
     */
    private $homepage;

    /**
     * @var String
     *
     * Event name
     */
    private $event;

    /**
     * @var Symfony\Bundle\FrameworkBundle\Routing\Router
     *
     * Router service
     */
    private RouterInterface $router;

    private Request $request;

    private User $user;

    /**
     * @param string $event
     * @param RouterInterface $router
     * @param Request $request
     */
    public function __construct(
        string $event,
        RouterInterface $router,
        Request $request,
        ?User $user = null)
    {
        $this->event = $event;
        $this->router = $router;
        $this->request = $request;
        $this->user = $user;
        $this->homepage = $this->router->generate('index');
    }

    /**
     * Exec browser redirection
     *
     * @return RedirectResponse     [type]  Redirection response
     */
    public function redirect(): RedirectResponse
    {
        if ($this->event == "" || !isset(UserRedirections::REDIRECTIONS_BY_EVENTS[$this->event])) {
            // Redirect to homepage
            return new RedirectResponse($this->homepage);
        }

        // Redirect by event
        return $this->redirectByEvent();
    }

    /**
     * Redirect by event
     *
     * @return RedirectResponse     [type]  Redirection response
     */
    private function redirectByEvent(): RedirectResponse
    {
        $redirectionData = UserRedirections::REDIRECTIONS_BY_EVENTS[$this->event];

        if($this->event === 'user_login_successful' && $this->user && ($this->user->isAdmin() || $this->user->isEditor())){
            return new RedirectResponse($this->router->generate('reaccion_cms_admin_index'));
        }

        if ($redirectionData['type'] === "route") {
            $routeUrl = $this->router->generate($redirectionData['value']);
            return new RedirectResponse($routeUrl);
        } else if ($redirectionData['type'] === "referrer") {
            // Referrer redirection
//            $refererUrl = $this->request->headers->get('referer') ?? null;

//            if (!$refererUrl) {
            return new RedirectResponse($this->homepage);
//            }
//            return new RedirectResponse($refererUrl);
        } else if ($redirectionData['type'] === "url") {
            return new RedirectResponse($redirectionData['value']);
        }
    }
}
