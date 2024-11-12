<?php

namespace App\Controller\Admin;


use App\Entity\Campus;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CampusCrudController extends AbstractController
{

    #[Route('/admin/campus', name: 'app_campus')]
    public function index(CampusRepository $campusRepository): Response
    {

        $campus = $campusRepository->findAll();


        return $this->render('admin/campus_crude/detail.html.twig',[

                'campusList' => $campus,

            ]
        );
    }

    #[Route('admin/campus-create', name: 'campus_create', methods: ['GET', 'POST'])]
    public function create(Request $request,EntityManagerInterface $em): Response
    {

        $name = $request->request->get('nom');
        $campus = new Campus();
        $campus->setNom($name);
        $em->persist($campus);
        $em->flush();

        return $this->redirectToRoute('app_home');

    }

    #[Route('admin/campus-modifier/{id}', name: 'campus_modifier', requirements:['id'=>'\d+'],methods: ['GET','POST'])]
    public function modifierParticipant(int $id,Request $request, CampusRepository $campusRepository, EntityManagerInterface $em): Response
    {
        $campus = $campusRepository->find($id);
        if (!$campus) {

            throw $this->createNotFoundException('La ville demandée n\'a pas été trouvée.');
        }
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $campus->setNom($nom);
            $em->flush();

        }
        return $this->redirectToRoute('app_home');


    }
    #[Route('admin/campus-suprimer/{id}', name: 'campus_suprimer', requirements:['id'=>'\d+'],methods: ['GET'])]
    public function deleteParticipant(int $id, CampusRepository $campusRepository, EntityManagerInterface $em): Response
    {

        $campus = $campusRepository->find($id);
        if(!$campus){
            throw $this->createNotFoundException('La ville demandée n\'a pas été trouvée.');
        }
        $em->remove($campus);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }
}
