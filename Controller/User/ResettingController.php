<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Controller\User;

	use FOS\UserBundle\Form\Factory\FormFactory;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use FOS\UserBundle\Util\TokenGeneratorInterface;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Constants\Cookies;
	use ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserLoginType;
	use ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserRegisterType;
	use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
	use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
	use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

	class ResettingController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator, TokenGeneratorInterface $tokenGenerator)
		{
			$accountUsername = ($request->request->get("username")) ?? '';

			if(strlen($accountUsername))
			{
				return $this->get("reaccion_cms.resetting.controller")->sendEmailAction($request, $tokenGenerator);
			}

			$sitename = $this->get("reaccion_cms.config")->get("site_name");
			$seo = [
				'title' => $translator->trans("user_resetting.seo_title", ['%sitename%' => $sitename])
			];

			$view = $this->get("reaccion_cms.theme")->getConfigView("resetting", true);
			$vars = [
				'seo' 		=> $seo,
				'username'  => $accountUsername
			];

			return $this->render($view, $vars);
		}

		public function checkEmail(Request $request)
		{
			$view = $this->get("reaccion_cms.theme")->getConfigView("resetting_check", true);
			return $this->get("reaccion_cms.resetting.controller")->checkEmailAction($request, $view);
		}

		public function reset(Request $request, $token)
		{
			$view = $this->get("reaccion_cms.theme")->getConfigView("resetting_reset", true);
			return $this->get("reaccion_cms.resetting.controller")->resetAction($request, $token, $view);
		}
	}