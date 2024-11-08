<?php

namespace App\Controller\Admin;

use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ParticipantRepository $participantRepository, CampusRepository $campusRepository): Response
    {
        $participants = $participantRepository->findAll();
        $campus = $campusRepository->findAll();
        return $this->render('admin/index.html.twig',[
            'participants' => $participants,
                'campusList' => $campus
            ]
        );
    }
}
