<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Controller\User;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Constants\Cookies;
	use ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserLoginType;
	use ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserRegisterType;
	use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
	use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
	use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

	class RegistrationController extends Controller
	{
		/**
		 * Register a new user
		 */
		public function index(Request $request, TranslatorInterface $translator)
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
				'form' 		=> $form->createView(),
				'seo' 		=> $seo
			];

			return $this->render($view, $vars);
		}
	}