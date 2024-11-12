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
    } #[Route('admin/ville-add', name: 'ville_add', methods: ['GET', 'POST'])]
public function create(Request $request,EntityManagerInterface $em): Response
{

    $name = $request->request->get('nom');
    $postalCode = $request->request->get('codePostal');
    $ville = new Ville();
    $ville->setNom($name);
    $ville->setCodePostal($postalCode);
    $em->persist($ville);
    $em->flush();

    return $this->redirectToRoute('app_home');

}

    #[Route('admin/ville-modifier/{id}', name: 'ville_modifier', requirements:['id'=>'\d+'],methods: ['GET','POST'])]
    public function modifierParticipant(int $id,Request $request, VilleRepository $villeRepository, EntityManagerInterface $em): Response
    {
        $ville = $villeRepository->find($id);
        if (!$ville) {

            throw $this->createNotFoundException('La ville demandée n\'a pas été trouvée.');
        }
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $codePostal = $request->request->get('codePostal');
            $ville->setNom($nom);
            $ville->setCodePostal($codePostal);
            $em->flush();

        }
        return $this->redirectToRoute('app_home');


    }
    #[Route('admin/ville-suprimer/{id}', name: 'ville_suprimer', requirements:['id'=>'\d+'],methods: ['GET'])]
    public function deleteParticipant(int $id, VilleRepository $villeRepository, EntityManagerInterface $em): Response
    {

        $ville = $villeRepository->find($id);
        if(!$ville){
            throw $this->createNotFoundException('La ville demandée n\'a pas été trouvée.');
        }
        $em->remove($ville);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

}
