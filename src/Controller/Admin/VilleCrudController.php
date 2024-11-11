<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\Ville;
use App\Form\Admin\AddVilleType;
use App\Form\Admin\ModifyVilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VilleCrudController extends AbstractController
{

    #[Route('/admin/ville', name: 'app_ville')]
    public function index(VilleRepository $villeRepository): Response
    {

        $ville = $villeRepository->findAll();


        return $this->render('admin/ville_crude/detail.html.twig',[

                'villeList' => $ville,

            ]
        );
    }
    #[Route('/admin/ville-create', name: 'app_ville_create', methods: ['GET', 'POST'])]
    public function create(Request $request,EntityManagerInterface $em): Response
    {
        $ville = new Ville();
        $villeForm = $this->createForm(AddVilleType::class, $ville);
       $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {

            $em->persist($ville);
            $em->flush();
            return $this->redirectToRoute('app_admin');
        }


        return $this->render('admin/ville_crude/index.html.twig', [
            'villeForm' => $villeForm->createView(),
        ]);
    }

    /////////////////////////////
    ///
    #[Route('/admin/ville-modifier/{id}', name: 'app_ville_modifier', requirements:['id'=>'\d+'],methods: ['GET','POST'])]
    public function modifierParticipant(int $id,Request $request, VilleRepository $villeRepository, EntityManagerInterface $em): Response
    {
        $ville = $villeRepository->find($id);
        $villeForm = $this->createForm(ModifyVilleType::class, $ville);
        $villeForm ->handleRequest($request);
        if ($villeForm ->isSubmitted() && $villeForm ->isValid()) {
            if(!$ville){
                throw $this->createNotFoundException('ville not found');
            }

            $em->persist($ville);
            $em->flush();
            return $this->redirectToRoute('app_admin');}

        return $this->render('admin/ville_crude/modifier.html.twig', [
            "villeForm_detail"=>$villeForm->createView(),
        ]);
    }
    #[Route('/admin/ville-delete/{id}', name: 'app_ville_delete', requirements:['id'=>'\d+'],methods: ['GET'])]
    public function deleteParticipant(int $id, VilleRepository $villeRepository, EntityManagerInterface $em): Response
    {

        $ville = $villeRepository->find($id);
        if(!$ville){
            throw $this->createNotFoundException('Ville not found');
        }
        $em->remove($ville);
        $em->flush();
        return $this->redirectToRoute('app_admin');
    }
}
