<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{
    #[Route('/request', name: 'request_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // dump($request); // Ne jamais laisser un dump en prod sinon error 500 !!!

        $name = ucfirst($request->query->get('name', 'visiteur'));

        return $this->render('request/index.html.twig', [
            'name' => $name,
            'lastname' => ''
        ]);
    }

    #[Route('/request', name: 'request_index_with_post', methods: ['POST'])]
    public function indexWithPost(): Response
    {
        return new Response();
    }

    #[Route('/request/session', name: 'request_session')]
    public function session(Request $request): Response
    {
        $session = $request->getSession(); // SessionInterface

        if($request->query->has('action')) {

            switch($request->get('action')) {
                case 'create':
                    $session->set('name', 'Stéphane');
                    break;
                case 'clear':
                    $session->remove('name');
                    break;
            }

            // ici presque un post-to-get pattern
            return $this->redirectToRoute('request_session');
        }

        return $this->render('request/session.html.twig', [
            'name' => $session->get('name', 'Visiteur')  //$name ?? 'Visiteur'
        ]);
    }

    #[Route('/request/flashes', name: 'request_flashes')]
    public function flashes(): Response
    {
        $this->addFlash('success', 'Bravo ! Vous avez créé un message éclair');

        return $this->redirectToRoute('request_index');
    }

    // @Route('/request/{number}', name='request_number', requirements={'number': '\d+'})
    #[Route('/request/{number}', name: 'request_number', requirements: ['number' => '\d+'])]
    public function number(int $number): Response
    {
        return $this->render('request/number.html.twig', [
            'number' => $number * 1.20
        ]);
    }

    // /request/john10 => avec requirements: ['firstname' => '[a-z]+'] -> ne fonctionne pas

    #[Route('/request/{firstname}/{lastname}', name: 'request_hello_name', requirements: ['firstname' => '[a-z]+'],
        defaults: ['lastname' => "Doe"])]
    public function helloName(string $firstname, string $lastname = "Doe"): Response
    {
        $firstname = ucfirst($firstname);
        $lastname = strtoupper($lastname);

        return $this->render('request/index.html.twig', [
            'name' => $firstname,
            'lastname' => $lastname
        ]);
    }


}
