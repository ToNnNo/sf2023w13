<?php

namespace App\Controller;

use App\Classes\Person;
use App\Security\Voter\CityVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// @Sensio\Bundle\FrameworkExtraBundle\Configuration\Security("is_granted('ROLE_ADMIN')")
// @Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("ROLE_ADMIN", statusCode=404, message="Not Found")

// #[Security("is_granted('ROLE_TOTO')")]
// #[IsGranted('ROLE_USER', message: "Not Found Bro", statusCode: 404)]
#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN', statusCode: 404, message: "Not Found")]
    public function admin(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/other', name: 'other')]
    public function other(): Response
    {
        /*if( $this->isGranted('ROLE_USER') ) {
            return $this->redirectToRoute('profile_index');
        }

        if( $this->isGranted('ROLE_ADMIN') ) {
            return $this->redirectToRoute('profile_admin');
        }*/

        if( !$this->isGranted(CityVoter::CITY_LILLE, new Person("John", "Doe")) ){
            throw $this->createAccessDeniedException();
        }

        return $this->render('profile/index.html.twig');
    }
}
