<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationController extends AbstractController
{

    /*
     *  @Route({
     *      "fr": "/traduction"
     *      "en": "/translation"
     *  }, name="translation_index")
     */

    // #[Route('/{_locale}/translation', name: 'translation_index')]
    #[Route([
        "fr" => "/traduction",
        "en" => "/translation"
    ], name: 'translation_index')]
    public function index(TranslatorInterface $translator): Response
    {
        $message = $translator->trans('Hello world');

        return $this->render('translation/index.html.twig', [
            'message' => $message,
            'name' => "John"
        ]);
    }

    #[Route('/{_locale}/icu', name: 'translation_icu')]
    public function icu(): Response
    {
        return $this->render('translation/icu.html.twig', [
            'date' => new \DateTime()
        ]);
    }
}
