<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }


    #[Route('/categorie/add', name: 'add_categorie')]
    public function add(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('afficher_categorie');
        }

        return $this->render('Categorie/add.html.twig', [
            'f' => $form->createView(),
        ]);
    }


    #[Route('/categorie/modifier/{{id}}', name: 'modifier_categorie')]
    public function modifier(Request $request, CategorieRepository $repository, $id, EntityManagerInterface $entityManager): Response
    {


        $categorie = $repository->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->flush();
            return $this->redirectToRoute('afficher_categorie');
        }


        return $this->render('Categorie/modifier_categorie.html.twig', [
            'f' => $form->createView()

        ]);
    }



    #[Route('/categorie/supprimer/{id}', name: 'supprimer_categorie')]
    public function supprimer_specialite(CategorieRepository  $rp, $id, Request $request, EntityManagerInterface $em)
    {
        $categorie = $rp->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('afficher_categorie');
    }
    #[Route('/categorie/afficher', name: 'afficher_categorie')]
    public function afficher_specialite(CategorieRepository  $rp): Response
    {


        $categorie = $rp->findAll();

        return $this->render('Categorie/afficher_categorie.html.twig', [
            'categorie' => $categorie
        ]);
    }
}
