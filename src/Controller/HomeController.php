<?php

namespace App\Controller;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /*
     * @Cache(public=true, must-revalidate=true, maxage: ... )
     */

    #[Route('/', name: 'home_index')]
    #[Cache()]
    public function index(): Response
    {
        $response = $this->render('home/index.html.twig', []);

        $now = new \DateTime();

        $expire = new \DateTime();
        $expire->modify('+24 hours');
        // dump($expire);

        /*$response
            ->setPublic()
            ->setDate($now)
            ->setExpires($expire)
        ;*/

        $response
            ->setPublic()
            ->setSharedMaxAge($expire->getTimestamp());

        return $response;
    }
}
