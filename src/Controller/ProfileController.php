<?php

namespace App\Controller;


use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile_update', methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager,#[Autowire('%photo_dir%')] string $photoDir): Response
    {

        $participant = $this->getUser();
        $profileForm = $this->createForm(ProfileType::class, $participant);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {

            $photo = $profileForm->get('image')->getData();
            if($photo)
            {

                $fileName = uniqid().'.'.$photo->guessExtension();
                $photo->move($photoDir,$fileName);
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
    #[Route('/profile/cancel', name: 'app_profile_cancel', methods: ['GET', 'POST'])]
    public function cancel(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}