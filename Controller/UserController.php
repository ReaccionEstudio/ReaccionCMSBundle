<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserRegisterType;

	class UserController extends Controller
	{
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

				if($newUser)
				{
					// redirect to home page
					return $this->redirectToRoute("index");
				}
				
				var_dump($newUser);
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
	}