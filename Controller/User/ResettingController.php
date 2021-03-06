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
	use ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserSettingsType;
	use ReaccionEstudio\ReaccionCMSBundle\Form\Users\UserRegisterType;
	use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
	use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
	use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

	class ResettingController extends Controller
	{
		/**
		 * @var TranslatorInterface
		 */
		private $translator;

		/**
		 * @var TokenGeneratorInterface
		 */
		private $tokenGenerator;

		/**
		 * Constructor
		 */
		public function __construct(TranslatorInterface $translator, TokenGeneratorInterface $tokenGenerator)
		{
			$this->translator = $translator;
			$this->tokenGenerator = $tokenGenerator;
		}

		public function index(Request $request)
		{
			$accountUsername = ($request->request->get("username")) ?? '';

			if(strlen($accountUsername))
			{
				return $this->get("reaccion_cms.resetting_service.controller")->sendEmailAction($request, $this->tokenGenerator);
			}

			$sitename = $this->get("reaccion_cms.config")->get("site_name");
			$seo = [
				'title' => $this->translator->trans("user_resetting.seo_title", ['%sitename%' => $sitename])
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
			return $this->get("reaccion_cms.resetting_service.controller")->checkEmailAction($request, $view);
		}

		public function reset(Request $request, $token)
		{
			$view = $this->get("reaccion_cms.theme")->getConfigView("resetting_reset", true);
			return $this->get("reaccion_cms.resetting_service.controller")->resetAction($request, $token, $view);
		}
	}
