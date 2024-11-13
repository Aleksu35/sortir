<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Security\Voter\DroitsBoutonsVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SortieController extends AbstractController
{

    #[Route('/create', name: 'app_sortie_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, EtatRepository $etatRepository): Response
    {
        $sortie = new Sortie();
        $sortie->setOrganisateur($this->getUser()); // Définit l'utilisateur courant comme organisateur
//        $em->clear();

        // Accéder aux états spécifiques depuis la base de données
        $etatSaved = $etatRepository->findOneBy(['libelle' => 'créée']);
        $etatPublished = $etatRepository->findOneBy(['libelle' => 'ouverte']);

        // Créer et gérer le formulaire
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            // Déterminer l'état de la sortie en fonction du bouton cliqué
            $this->handleEtat($request, $etatSaved, $etatPublished, $sortie);

            dump($sortie);
            // Sauvegarder la sortie
            $em->persist($sortie);
            $em->flush();

            // Message de succès et redirection
            $this->addFlash('success', "Sortie ajoutée avec succès");
            return $this->redirectToRoute('app_home');
        }

        // Retourner la vue avec le formulaire
        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    private function handleEtat(Request $request, $etatSaved, $etatPublished, Sortie $sortie): void
    {
        if ($request->request->get('save') !== null && $etatSaved) {
            $sortie->setEtat($etatSaved);
        } elseif ($request->request->get('publish') !== null && $etatPublished) {
            $sortie->setEtat($etatPublished);
        }
    }


    #[Route('/{id}/publier', name: 'app_sortie_publier', methods: ['GET'])]
    #[IsGranted(DroitsBoutonsVoter::PUBLISHED, 'sortie')]
    public function publier(Sortie $sortie, Request $request, EtatRepository $etatRepository, EntityManagerInterface $em): Response
    {
        // Récupérer l'état "ouverte" dans la DB
        $etatPublished = $etatRepository->findOneBy(['libelle' => 'ouverte']);
        $etatCreate = $etatRepository->findOneBy(['libelle' => 'créée']);

        // Vérifie si la sortie est bien dans l'état créée avant de la publier
        if ($sortie->getEtat() === $etatCreate) {
            // La change à l'état "ouverte"
            $sortie->setEtat($etatPublished);
            $em->persist($sortie);
            $em->flush();

            // Ajoute un message de succès
            $this->addFlash('success', 'La sortie a été publiée avec succès !');
        } else {
            // Ajoute un message d'erreur
            $this->addFlash('error', 'la sortie ne peut pas être publiée.');
        };
        // Redirection
        return $this->redirectToRoute('app_home');

    }

    #[Route('/{id}/modifier-sortie', name: 'modifier-sortie', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted(DroitsBoutonsVoter::EDIT, 'sortie')]
    public function update(Sortie $sortie, SortieRepository $sortieRepository, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifiez que l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupération de la sortie à modifier en fonction de son id présent dans l'url.
//        $sortie = $sortieRepository->find($id);
//        if (!$sortie) {
//            throw $this->createNotFoundException('La sortie est introuvable, désolé !');
//        }

        // Teste si l'utilisateur connecté est le même que l'utilisateur associé à la sortie
//        if ($sortie->getParticipants() !== $this->getUser()) {
//            throw $this->createAccessDeniedException();
//        }

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
            'sortie' => $sortie
        ]);
    }

    #[Route('/mes-sorties', name: 'mes-sorties', methods: ['GET'])]
    public function mesSorties(SortieRepository $sortieRepository): Response
    {
        // Récupérer les sorties dont l'utilisateur actuel est l'organisateur
        $sorties = $sortieRepository->findBy(['organisateur' => $this->getUser()]);

        return $this->render('sortie/mes-sorties.html.twig', [
            'sorties' => $sorties,

        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, SortieRepository $sortieRepository, Request $request, EntityManagerInterface $em): Response
    {
        $sortie = $sortieRepository->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée.');
        }

        // Vérifier que l'utilisateur connecté est le même que celui de la sortie ou un administrateur
        if (!($sortie->getParticipant() === $this->getUser() || $this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }

        // Vérifier le token CSRF pour la suppression
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->get('token'))) {
            $em->remove($sortie);
            $em->flush();

            $this->addFlash('success', 'Sortie supprimée avec succès !');
        } else {
            $this->addFlash('danger', 'Erreur de sécurité, la suppression n\'a pas pu être effectuée.');
        }

        return $this->redirectToRoute('mes-sorties');
    }

    /*
     * ***************************************************
     * AFFICHAGE DE DETAIL SORTIE COMMENCE LES WINNERS
     * ***************************************************
     * ***************************************************
     * ***************************************************
     * */

    #[Route('/showSortieDetail/{id}', name: 'showSortiedetail', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted(DroitsBoutonsVoter::VIEW, 'sortie')]
    public function showDetail(int $id, Sortie $sortie, SortieRepository $sortieRepository): Response
    {

        $sortie = $sortieRepository->find($id);

        if (!$sortie) {
            throw $this->createNotFoundException('Sortie not found');
        }
        return $this->render('Sortie/detail/index.html.twig', [
            "sortie_detail" => $sortie,
        ]);
    }

    #[Route('/{id}/inscription', name: 'inscription_sortie')]
    public function inscrire(int $id, SortieRepository $sortieRepository, EntityManagerInterface $em): Response
    {
        // Récupérer la sortie par son ID
        $sortie = $sortieRepository->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException('La sortie n\'existe pas.');
        }

        // Vérifier que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirige vers la connexion si non connecté
        }

        // Vérifier si l'utilisateur est déjà inscrit
        if ($sortie->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cette sortie.');
            return $this->redirectToRoute('app_home'); // Rediriger avec message d'avertissement
        }

        // Ajouter l'utilisateur à la liste des participants
        $sortie->addParticipant($user);

        // Sauvegarder les modifications
        $em->flush();

        // Ajouter un message flash pour indiquer l'inscription réussie
        $this->addFlash('success', 'Vous êtes inscrit à la sortie !');

        // Rediriger vers la page de la sortie ou la page d'accueil
        return $this->redirectToRoute('app_home');
    }

}