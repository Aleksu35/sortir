<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Form\Admin\AddParticipantType;
use App\Form\Admin\ModifyParticipantType;
use App\Repository\ParticipantRepository;
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

    /////////////////////////////
    ///
    #[Route('/admin/participant-modifier/{id}', name: 'app_participant_modifier', requirements:['id'=>'\d+'],methods: ['GET','POST'])]
    public function modifierParticipant(int $id,Request $request, ParticipantRepository $participantRepository, EntityManagerInterface $em): Response
    {

        $participant = $participantRepository->find($id);
        $participantForm = $this->createForm(ModifyParticipantType::class, $participant);
        $participantForm ->handleRequest($request);
        if ($participantForm ->isSubmitted() && $participantForm ->isValid()) {
            if(!$participant){
            throw $this->createNotFoundException('Participant not found');
        }

            $em->persist($participant);

            $em->flush();
            return $this->redirectToRoute('app_admin');}

        return $this->render('admin/participant_crude/modifier.html.twig', [
            "participantForm_detail"=>$participantForm->createView(),
        ]);
    }
 #[Route('/admin/participant-delete/{id}', name: 'app_participant_delete', requirements:['id'=>'\d+'],methods: ['GET'])]
    public function deleteParticipant(int $id, ParticipantRepository $participantRepository, EntityManagerInterface $em): Response
    {

        $participant = $participantRepository->find($id);
        if(!$participant){
        throw $this->createNotFoundException('Participant not found');
        }
        $em->remove($participant);
        $em->flush();
        return $this->redirectToRoute('app_admin');
    }

}