<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SortieController extends AbstractController
{

    #[Route('/create', name: 'app_sortie_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $sortie = new Sortie();
        $sortie->setParticipant($this->getUser());

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $sortie->setPublished(true);

            $em->persist($sortie);
            $em->flush();


            $this->addFlash('success', "Sortie ajoutée avec succès");

            // Redirect to the list of sorties after saving
            return $this->redirectToRoute('app_home');
        }

        // Render the form when it's not submitted or not valid
        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }


}

