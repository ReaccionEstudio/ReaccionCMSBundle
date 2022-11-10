<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Controller\Language;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class LanguageController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(String $language = "", Request $request)
    {
        $result = $this->get("reaccion_cms.language")->updateLanguage($language);

        if (!$result) {
            // change_language
            $this->addFlash('language_switch_error', $this->translator->trans('change_language.error'));
        }

        // get reference
        $refererUrl = $request->headers->get('referer');

        if (preg_match('/\/admin\//', $refererUrl)) {
            return $this->get("reaccion_cms.user")->redirect('user_updated_language');
        } else {
            $refererRoute = $request->query->get("appRoute");
            $refererRouteSlug = $request->query->get("appRouteSlug");

            // Check if page translation exists in the current page translation group
            return $this->get("reaccion_cms.language_page_translation_group_redirection")->redirectByTranslationGroup($language, $refererUrl, $refererRoute, $refererRouteSlug);
        }
    }
}
