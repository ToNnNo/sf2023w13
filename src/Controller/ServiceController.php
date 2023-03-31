<?php

namespace App\Controller;

use App\Service\Crypto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    private $crypto;

    public function __construct(Crypto $crypto)
    {
        $this->crypto = $crypto;
    }

    #[Route('/service', name: 'service_index')]
    public function index(): Response
    {
        $message = "Quand je retire mes lunettes je suis superman !";

        // dump($this->getParameter('message'));

        $ciphertext = $this->crypto->encode($message);

        return $this->render('service/index.html.twig', [
            'cipher_text' => $ciphertext
        ]);
    }
}
