<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserLoginType;
	use App\ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserRegisterType;
	use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
	use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
	use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

	class UserController extends Controller
	{
		/**
		 * Register a new user
		 */
		public function register(Request $request, TranslatorInterface $translator)
		{
			$sitename = $this->get("reaccion_cms.config")->get("site_name");
			$seo = [
				'title' => $translator->trans("user_register.seo_title", ['%sitename%' => $sitename])
			];

			// form
			$form = $this->createForm(UserRegisterType::class);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$username 	= $form['username']->getData();
				$email 		= $form['email']->getData();
				$password 	= $form['password']->getData();
				
				// create new user
				$newUser = $this->get("reaccion_cms.user")->createNewUserIfAvailable($username, $email, $password);

				if($newUser)
				{
					// sign in new user

					$token = new UsernamePasswordToken($newUser, null, 'main', $newUser->getRoles());
        			$this->get('security.token_storage')->setToken($token);

					// redirect to home page
					return $this->redirectToRoute("index");
				}
			}

			// view
			$view = $this->get("reaccion_cms.theme")->getConfigView("register", true);
			$vars = [
				'language' 	=> 'en',
				'form' 		=> $form->createView(),
				'seo' 		=> $seo
			];

			return $this->render($view, $vars);
		}

		/**
		 * Login existing user
		 */
		// https://ourcodeworld.com/articles/read/459/how-to-authenticate-login-manually-an-user-in-a-controller-with-or-without-fosuserbundle-on-symfony-3
		public function login(Request $request, TranslatorInterface $translator, EncoderFactoryInterface $encoder)
		{
			$seo = ['title' => 'Sign in in {sitename}'];

			// form
			$form = $this->createForm(UserLoginType::class);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$username 	= $form['username']->getData();
				$password 	= $form['password']->getData();

				// get user manager
				$userManager = $this->get('fos_user.user_manager');
				$user = $userManager->findUserByUsernameOrEmail($username);

				// check credentials
    			$isValidPassword = $encoder->getEncoder($user)->isPasswordValid($user->getPassword(), $password, $user->getSalt());

    			if($isValidPassword)
    			{
    				// TODO: not working
    				$session = $this->get('session');
    				$token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    				$this->get('security.token_storage')->setToken($token);
    				$session->set('_security_main', serialize($token));
    				$session->save();

    				$event = new InteractiveLoginEvent($request, $token);
		            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
  	  			}
    			else
    			{
    				$this->addFlash('signin_error', $translator->trans('signin_errors.invalid_credentials'));
    			}
			}

			// view
			$view = $this->get("reaccion_cms.theme")->getConfigView("login", true);
			$vars = [
				'language' 	=> 'en',
				'form' 		=> $form->createView(),
				'seo' 		=> $seo
			];

			return $this->render($view, $vars);
		}
	}