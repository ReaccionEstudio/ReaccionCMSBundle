<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller\User;

	use FOS\UserBundle\FOSUserEvents;
	use FOS\UserBundle\Event\FormEvent;
	use FOS\UserBundle\Mailer\MailerInterface;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	use FOS\UserBundle\Model\UserManagerInterface;
	use FOS\UserBundle\Event\GetResponseUserEvent;
	use Symfony\Component\Form\FormFactoryInterface;
	use FOS\UserBundle\Util\TokenGeneratorInterface;
	use FOS\UserBundle\Event\FilterUserResponseEvent;
	use FOS\UserBundle\Form\Factory\FactoryInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use FOS\UserBundle\Event\GetResponseNullableUserEvent;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
	            return new RedirectResponse($this->generateUrl('user_resetting'));
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
	    public function resetAction(Request $request, $token, $view)
	    {
	    	$formFactory = $this->get("resetting.form.factory");
	    	$userManager = $this->get("fos_user.user_manager");
	    	$eventDispatcher = $this->get("event_dispatcher");
	        $user = $userManager->findUserByConfirmationToken($token);

	        if (null === $user) {
	            return new RedirectResponse($this->container->get('router')->generate('user_login'));
	        }

	        $event = new GetResponseUserEvent($user, $request);
	        $eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

	        if (null !== $event->getResponse()) {
	            return $event->getResponse();
	        }

	        $form = $formFactory->createForm();
	        $form->setData($user);

	        $form->handleRequest($request);

	        if ($form->isSubmitted() && $form->isValid()) 
	        {
	            $event = new FormEvent($form, $request);
	            $eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

	            $userManager->updateUser($user);

	            if (null === $response = $event->getResponse()) {
	                $url = $this->generateUrl('index'); // TODO: set /profile route
	                $response = new RedirectResponse($url);
	            }

	            $eventDispatcher->dispatch(
	                FOSUserEvents::RESETTING_RESET_COMPLETED,
	                new FilterUserResponseEvent($user, $request, $response)
	            );

	            return $response;
	        }

	        return $this->render($view, array(
	            'token' => $token,
	            'form' => $form->createView()
	        ));
	    }
	}