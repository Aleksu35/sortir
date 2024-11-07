<?php

namespace App\Controller;


use App\Form\ProfileType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile_update', methods: ['GET', 'POST'])]
    public function update(Request $request,UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager,#[Autowire('%photo_dir%')] string $photoDir): Response
    {

        $participant = $this->getUser();
        $profileForm = $this->createForm(ProfileType::class, $participant,['passwordHasher'=>$passwordHasher]);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {

            $photo = $profileForm->get('image')->getData();

            if ($photo) {
                $filesystem = new Filesystem();


                if ($participant->getFilename()) {
                    $oldImagePath = $photoDir . '/' . $participant->getFilename();
                    if ($filesystem->exists($oldImagePath)) {
                        $filesystem->remove($oldImagePath);
                    }
                }


                $fileName = uniqid() . '.' . $photo->guessExtension();
                $photo->move($photoDir, $fileName);


                $participant->setFilename($fileName);
            }

            $entityManager->persist($participant);
            $entityManager->flush();


            return $this->redirectToRoute('app_home');
        }
        return $this->render('profile/index.html.twig', [
            'profileForm' => $profileForm->createView(),
            'participant' => $participant,
        ]);
    }

    #[Route('/showProfile/{id}', name: 'app_profile_showdetail', requirements:['id'=>'\d+'],methods: ['GET'])]
    public function showDetail(int $id, ParticipantRepository $participantRepository): Response
    {

        $participant = $participantRepository->find($id);

              if(!$participant){
                   throw $this->createNotFoundException('Participant not found');
              }
                return $this->render('profile/detail/index.html.twig', [
                    "participant_detail"=>$participant
               ]);
    }



    #[Route('/profile/cancel', name: 'app_profile_cancel', methods: ['GET', 'POST'])]
    public function cancel(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
