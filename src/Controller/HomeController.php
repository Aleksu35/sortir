<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SortieRepository $sortieRepository): Response
    {
        // Récupérer toutes les sorties
        $sorties = $sortieRepository->findAll(); // Récupère toutes les sorties de la base de données

        return $this->render('home/index.html.twig', [
            'sorties' => $sorties, // Passer les sorties à la vue
        ]);
    }
}

// oui oui non non