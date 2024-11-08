<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Form\Admin\AddParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ParticipantCrudeController extends AbstractController                       //NEED TO HASH PASSWORD
{
    #[Route('/admin/participant-create', name: 'app_participant_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $participant = new Participant();


        $participantForm = $this->createForm(AddParticipantType::class, $participant);
        $participantForm ->handleRequest($request);

        if ($participantForm ->isSubmitted() && $participantForm ->isValid()) {
            $em->persist($participant);
            $this->addFlash('success', "Une Nouvelle Participant ajoutée avec succès");
            $em->flush();


        }


        return $this->render('admin/participant_crude/index.html.twig', [
            'participantForm' => $participantForm->createView(),
        ]);
    }
}