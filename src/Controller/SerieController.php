<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_', requirements: ['id' => '\d+'])]
class SerieController extends AbstractController
{
    /*private $serieRepository;

    public function __construct(SerieRepository $serieRepository)
    {
        $this->serieRepository = $serieRepository;
    }*/

    public function __construct(private SerieRepository $serieRepository) { }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        // $series = $this->serieRepository->findAll();
        $series = $this->serieRepository->findAllWithNotes();

        return $this->render('serie/index.html.twig', [
            'series' => $series
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $serie = new Serie();
        $form = $this->createForm(SerieType::class, $serie);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ) {
            $this->serieRepository->save($serie, true);

            $this->addFlash('success', 'La série a bien été enregistrée');
            return $this->redirectToRoute('serie_index');
        }

        return $this->render('serie/edit.html.twig', [
            'formView' => $form
        ]);
    }

    #[Route('/{id}/detail', name: 'detail')]
    public function detail(Serie $serie): Response
    {
        // ParamConverter => transforme la clé transmis dans l'url en une instance
        // de l'entité demandé

        return $this->render('serie/detail.html.twig', [
            'serie' => $serie
        ]);
    }
}
