<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller\User;

	use FOS\UserBundle\Event\FilterUserResponseEvent;
	use FOS\UserBundle\Event\FormEvent;
	use FOS\UserBundle\Event\GetResponseNullableUserEvent;
	use FOS\UserBundle\Event\GetResponseUserEvent;
	use FOS\UserBundle\Form\Factory\FactoryInterface;
	use FOS\UserBundle\FOSUserEvents;
	use FOS\UserBundle\Mailer\MailerInterface;
	use FOS\UserBundle\Model\UserManagerInterface;
	use FOS\UserBundle\Util\TokenGeneratorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;

	class ResettingServiceController extends Controller
	{
	    /**
	     * Request reset user password: submit form and send email.
	     *
	     * @param Request $request
	     *
	     * @return Response
	     */
	    public function sendEmailAction(Request $request, TokenGeneratorInterface $tokenGenerator)
	    {
	        $username = $request->request->get('username');
	    	$eventDispatcher = $this->get("event_dispatcher");
	    	$userManager = $this->get("fos_user.user_manager");

	        $user = $userManager->findUserByUsernameOrEmail($username);

	        $event = new GetResponseNullableUserEvent($user, $request);
	        $eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

	        if (null !== $event->getResponse()) {
	            return $event->getResponse();
	        }

	        $retryTtl = $this->getParameter("fos_user.resetting.retry_ttl");

	        if (null !== $user && !$user->isPasswordRequestNonExpired($retryTtl)) {
	            $event = new GetResponseUserEvent($user, $request);
	            $eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_REQUEST, $event);

	            if (null !== $event->getResponse()) {
	                return $event->getResponse();
	            }

	            if (null === $user->getConfirmationToken()) {
	                $user->setConfirmationToken($tokenGenerator->generateToken());
	            }

	            $event = new GetResponseUserEvent($user, $request);
	            $eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);

	            if (null !== $event->getResponse()) {
	                return $event->getResponse();
	            }

	            $this->get("reaccion_cms.user_mailer")->sendResettingEmailMessage($user);
	            $user->setPasswordRequestedAt(new \DateTime());
	            $userManager->updateUser($user);

	            $event = new GetResponseUserEvent($user, $request);
	            $eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_COMPLETED, $event);

	            if (null !== $event->getResponse()) {
	                return $event->getResponse();
	            }
	        }

	        return new RedirectResponse($this->generateUrl('user_resetting_check_email', array('username' => $username)));
	    }

	    /**
	     * Tell the user to check his email provider.
	     *
	     * @param Request $request
	     *
	     * @return Response
	     */
	    public function checkEmailAction(Request $request, String $view)
	    {
	        $username = $request->query->get('username');

	        if (empty($username)) {
	            // the user does not come from the sendEmail action
	            return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
	        }

	        $retryTtl = $this->getParameter("fos_user.resetting.retry_ttl");

	        return $this->render($view, array(
	            'tokenLifetime' => ceil($retryTtl / 3600)
	        ));
	    }

	    /**
	     * Reset user password.
	     *
	     * @param Request $request
	     * @param string  $token
	     *
	     * @return Response
	     */
	    // TODO: customize this method.
	    public function resetAction(Request $request, $token)
	    {
	        $user = $this->userManager->findUserByConfirmationToken($token);

	        if (null === $user) {
	            return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login'));
	        }

	        $event = new GetResponseUserEvent($user, $request);
	        $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

	        if (null !== $event->getResponse()) {
	            return $event->getResponse();
	        }

	        $form = $this->formFactory->createForm();
	        $form->setData($user);

	        $form->handleRequest($request);

	        if ($form->isSubmitted() && $form->isValid()) {
	            $event = new FormEvent($form, $request);
	            $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

	            $this->userManager->updateUser($user);

	            if (null === $response = $event->getResponse()) {
	                $url = $this->generateUrl('fos_user_profile_show');
	                $response = new RedirectResponse($url);
	            }

	            $this->eventDispatcher->dispatch(
	                FOSUserEvents::RESETTING_RESET_COMPLETED,
	                new FilterUserResponseEvent($user, $request, $response)
	            );

	            return $response;
	        }

	        return $this->render('@FOSUser/Resetting/reset.html.twig', array(
	            'token' => $token,
	            'form' => $form->createView(),
	        ));
	    }
	}