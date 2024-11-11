<?php

namespace App\Controller\Admin;


use App\Entity\Campus;
use App\Form\Admin\AddCampusType;
use App\Form\Admin\ModifyCampusType;
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
    #[Route('/admin/campus-create', name: 'app_campus_create', methods: ['GET', 'POST'])]
    public function create(Request $request,EntityManagerInterface $em): Response
    {
        $campus = new Campus();
        $campusForm = $this->createForm(AddCampusType::class, $campus);
        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {

            $em->persist($campus);
            $em->flush();
            return $this->redirectToRoute('app_campus');
        }


        return $this->render('admin/campus_crude/index.html.twig', [
            'campusForm' => $campusForm->createView(),
        ]);
    }

    /////////////////////////////
    ///
    #[Route('/admin/campus-modifier/{id}', name: 'app_campus_modifier', requirements:['id'=>'\d+'],methods: ['GET','POST'])]
    public function modifierCampus(int $id,Request $request, CampusRepository $campusRepository, EntityManagerInterface $em): Response
    {
        $campus = $campusRepository->find($id);
        $campusForm = $this->createForm(ModifyCampusType::class, $campus);
        $campusForm ->handleRequest($request);
        if ($campusForm ->isSubmitted() && $campusForm ->isValid()) {

            if(!$campus){
                throw $this->createNotFoundException('Campus not found');
            }

            $em->persist($campus);
            $em->flush();
            return $this->redirectToRoute('app_campus');}

        return $this->render('admin/campus_crude/modifier.html.twig', [
            "campusForm_detail"=>$campusForm->createView(),
        ]);
    }
    #[Route('/admin/campus-delete/{id}', name: 'app_campus_delete', requirements:['id'=>'\d+'],methods: ['GET'])]
    public function deleteCampus(int $id, CampusRepository $campusRepository, EntityManagerInterface $em): Response
    {

        $campus = $campusRepository->find($id);
        if(!$campus){
            throw $this->createNotFoundException('Campus not found');
        }
        $em->remove($campus);
        $em->flush();
        return $this->redirectToRoute('app_campus');
    }
}
