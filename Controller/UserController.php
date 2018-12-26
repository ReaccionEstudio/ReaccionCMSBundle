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

				if($newUser === true)
				{
					// sign in new user
					$this->get("reaccion_cms.authentication")->setUser($newUser)->authenticate(true);

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
		public function login(Request $request, TranslatorInterface $translator, EncoderFactoryInterface $encoder)
		{
			// TODO: if user is already logged in, redirect to home
			$seo = ['title' => $translator->trans("signin.title") ];

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

				if( ! empty($user))
				{
					// check credentials
	    			$isValidPassword = $encoder->getEncoder($user)->isPasswordValid($user->getPassword(), $password, $user->getSalt());

	    			if($isValidPassword)
	    			{
	    				$this->get("reaccion_cms.authentication")->setUser($user)->authenticate(true);

	    				// redirect to home page
						return $this->redirectToRoute("index");
	  	  			}
	    			else
	    			{
	    				$this->addFlash('signin_error', $translator->trans('signin.invalid_credentials'));
	    			}
				}
				else
				{
	    			$this->addFlash('signin_error', $translator->trans('signin.user_doesnt_exists', ['%username%' => $username]));
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