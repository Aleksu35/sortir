<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Form\Admin\AddParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ParticipantCrudeController extends AbstractController
{
    #[Route('/admin/participant-create', name: 'app_participant_create', methods: ['GET', 'POST'])]
    public function create(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        $participant = new Participant();
        $participantForm = $this->createForm(AddParticipantType::class, $participant);
        $participantForm ->handleRequest($request);

        if ($participantForm ->isSubmitted() && $participantForm ->isValid()) {
            $plainPassword = $participantForm->get('plainPassword')->getData();
            $role = $participant->getRoles();

            if (!$role) {
                $role = 'ROLE_USER'; // Default
            }
            $participant->setRoles($role);

            // encode
            $participant->setPassword($userPasswordHasher->hashPassword($participant, $plainPassword));
            $em->persist($participant);
            $this->addFlash('success', "Une Nouvelle Participant ajoutée avec succès");
            $em->flush();
            return $this->redirectToRoute('app_admin');
        }


        return $this->render('admin/participant_crude/index.html.twig', [
            'participantForm' => $participantForm->createView(),
        ]);
    }
}