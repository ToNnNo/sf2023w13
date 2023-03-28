<?php

namespace App\Controller;

use App\Classes\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwigController extends AbstractController
{
    #[Route('/twig', name: 'twig_index')]
    public function index(): Response
    {
        $name = "John Smith";

        $html = "<b>Hello World</b>";

        $address = [
            'street' => '46 rue des canonniers',
            'zip' => '59800',
            'city' => 'Lille'
        ];

        $person = new Person("Pierre", "Dupond");

        return $this->render('twig/index.html.twig', [
            'name' => $name,
            'html' => $html,
            'numbers' => range(1, 10),
            'address' => $address,
            'person' => $person,
            'date' => new \DateTime()
        ]);
    }

    #[Route('/twig/sub-request/{limit}', name: 'twig_sub_request')]
    public function subRequest(int $limit = 10): Response
    {
        $list = [
            'Alexis', 'Aurélien', 'Benjamin', 'Christophe', 'David D',
            'David T', 'Sylvain', 'Thierry', 'Vincent', 'Fred'
        ];

        if( $limit < count($list) ) {
            $list = array_slice($list, 0, $limit);
        }

        // sleep(10);

        return $this->render('twig/sub-request.html.twig', [
            'list' => $list
        ]);
    }
}
