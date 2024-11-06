<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
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

    #[Route('/{id}/modifier-sortie', name: 'modifier-sortie', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(int $id, SortieRepository $sortieRepository, Request $request, EntityManagerInterface $em, ParticipantRepository $participantRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $participant = $participantRepository->find($id);


        // Récupération de la sortie à modifier en fonction de son id présent dans l'url.
        $sortie = $sortieRepository->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException('La sortie est introuvable, désolé !');
        }

        // Teste si l'utilisateur connecté est le même que l'utilisateur associé à la sortie
        if ($sortie->getParticipant() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        // Création et gestion du formulaire associé à notre objet sortie.
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        // On teste si le formulaire est soumis et s'il est valide
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            // Mise à jour de la date de modification de la sortie
            $sortie->setDateUpdated(new \DateTimeImmutable());

            // Sauvegarde en BDD
            $em->flush();

            // Afficher un message de confirmation
            $this->addFlash('success', "La sortie a bien été modifiée !");

            // Redirige vers la page de détails de la sortie modifiée
            return $this->redirectToRoute('app_home', ['id' => $sortie->getId()]);
        }

        // Affiche le formulaire
        return $this->render('sortie/modifier-sortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' => $sortie,
            'participant' => $participant
        ]);
    }
}
