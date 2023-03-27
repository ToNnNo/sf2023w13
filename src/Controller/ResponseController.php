<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends AbstractController
{
    // @Route('/response', name='app_response')

    #[Route('/response', name: 'response_index')]
    public function index(): Response
    {
        return $this->render('response/index.html.twig', [
            'controller_name' => 'ResponseController',
        ]);
    }

    #[Route('/response/raw', name: 'response_raw')]
    public function raw(): Response
    {
        $html_title = "Réponse <small>(Symfony Response)</small>";

        return new Response(<<<EOF
<html lang="fr">
<head>
    <title>Réponse Brute</title>
    <meta charset="utf-8" />
</head>
<body>
    <h1>$html_title</h1> 
    <p>Obtenu depuis une instance de <b>Symfony\Component\HttpFoundation\Response</b></p>
</body>    
</html>
EOF);
    }

    #[Route('/response/redirection', name: 'response_redirection')]
    public function redirection(): RedirectResponse
    {
        // return new RedirectResponse('/response/raw'); // correct mais pas excellent
        return new RedirectResponse($this->generateUrl('response_raw')); // parfait !
    }

    #[Route('/response/redirection/helper', name: 'response_redirection_helper')]
    public function redirectionHelper(): Response
    {
        // return $this->redirect($this->generateUrl('response_raw'));
        return $this->redirectToRoute('response_raw');
    }

    #[Route('/response/json', name: 'response_json')]
    public function jsonResponse(): JsonResponse
    {
        $content = ['firstname' => 'Stéphane', 'lastname' => 'Menut'];

        return new JsonResponse($content);
    }

    #[Route('/response/json/helper', name: 'response_json_helper')]
    public function jsonResponseHelper(): Response
    {
        $content = ['firstname' => 'Stéphane', 'lastname' => 'Menut'];

        return $this->json($content);
    }

    #[Route('/response/download', name: 'response_download')]
    public function download(): Response
    {
        $file = $this->getParameter('kernel.project_dir') . "/public/download/grogu.jpg";

        return $this->file($file);
    }

    #[Route('/response/500', name: 'response_500')]
    public function internalServerError(): Response
    {
        throw new \Exception("Ceci n'est pas une erreur ...");

        return new Response();
    }

    #[Route('/response/404', name: 'response_404')]
    public function notFound(): Response
    {
        throw $this->createNotFoundException("Cette page existe !");

        return new Response();
    }
}
