<?php

namespace App\Controller;

use App\Entity\FiltreSortie;
use App\Form\FiltreSortieType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, SortieRepository $sortieRepository): Response
    {
        // Créer un nouvel objet FiltreSortie
        $filtre = new FiltreSortie();

        // Créer le formulaire de filtre basé sur l'objet FiltreSortie
        $form = $this->createForm(FiltreSortieType::class, $filtre, [
            'method' => 'GET', // Utilise GET pour que les filtres apparaissent dans l'URL
        ]);
        $form->handleRequest($request);

        // Récupérer les sorties filtrées en fonction des données du formulaire
        $sorties = $form->isSubmitted() && $form->isValid()
            ? $sortieRepository->findWithFilters($filtre)
            : $sortieRepository->findAll();

        return $this->render('home/index.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView(),
        ]);
    }
}
