<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Constants\Cookies;

	class LanguageController extends Controller
	{
		public function index(String $language="", Request $request, TranslatorInterface $translator)
		{
			$result = $this->get("reaccion_cms.language")->updateLanguage($language);

			if( ! $result)
			{
				// change_language
				$this->addFlash('language_switch_error', $translator->trans('change_language.error'));
			}

			// get reference
			$refererUrl = $request->headers->get('referer');
			
			if( preg_match('/\/admin\//', $refererUrl) )
			{
				return $this->get("reaccion_cms.user")->redirect('user_updated_language');
			}
			else
			{
				$refererRoute = $request->query->get("appRoute");
				$refererRouteSlug = $request->query->get("appRouteSlug");

				// Check if page translation exists in the current page translation group
				return $this->get("reaccion_cms.language_page_translation_group_redirection")->redirectByTranslationGroup($language, $refererUrl, $refererRoute, $refererRouteSlug);
			}
		}
	}