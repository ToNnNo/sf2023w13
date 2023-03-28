<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctrineController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/doctrine', name: 'doctrine_index')]
    public function index(Request $request): Response
    {
        $post = (new Post())
            ->setTitle('Présentation de doctrine')
            ->setBody('Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Donec sed odio operae, eu vulputate felis rhoncus. Nec dubitamus multa iter quae et nos invenerat. Salutantibus vitae elit libero, a pharetra augue. Curabitur est gravida et libero vitae dictum.')
            ->setCreatedAt(new \DateTime())
        ;

        if( $request->query->has('save') ) {

            // persist() met en mémoire l'entité à enregistrer (insert ou update)
            $this->em->persist($post);
            // flush() execute les requêtes vers la bdd
            $this->em->flush();

            $this->addFlash('success', "L'article a bien été enregistré");

            return $this->redirectToRoute('doctrine_index');
        }

        return $this->render('doctrine/index.html.twig', [
            'post' => $post
        ]);
    }
}
