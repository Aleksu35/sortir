<?php

namespace App\Controller;


use App\Form\ProfileType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $participant = $this->getUser();
        $profileForm = $this->createForm(ProfileType::class, $participant);

        return $this->render('profile/index.html.twig', [
            'profileForm' => $profileForm->createView(),
        ]);
    }

    #[Route('/profile/update', name: 'app_profile_update', methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $participant = $this->getUser();
        $profileForm = $this->createForm(ProfileType::class, $participant);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {

            $imageFile = $profileForm->get('imageFile')->getData();
            if (($profileForm->has('deleteImage') && $profileForm['deleteImage']->getData()) || $imageFile) {

                $fileUploader->delete($this->getParameter('app.images_profile_directory'), $participant->getFilename());

                if ($imageFile) {
                    $participant->setFilename($fileUploader->upload($imageFile));
                } else {
                    $participant->setFilename(null);
                }
            }

            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('profile/index.html.twig', [
            'profileForm' => $profileForm->createView(),
        ]);
    }
#[Route('/profile/cancel', name: 'app_profile_cancel', methods: ['GET', 'POST'])]
public function cancel(): Response
{
    return $this->redirectToRoute('app_home');
}
}
