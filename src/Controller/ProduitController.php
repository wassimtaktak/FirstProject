<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieRepository;

class ProduitController extends AbstractController
{
    
    
    #[Route('/produit-cards', name: 'produit_cards')]
    public function cards(ProduitRepository  $rp): Response
    {
        $produit = $rp->findAll();

        return $this->render('produit/produitCards.html.twig', [
            'produit' => $produit
        ]);
    }

    #[Route('/produit-detail/{id}', name: 'produit_detail')]
    public function produit_detail($id, ProduitRepository $repository): Response
    {
        $produit = $repository->find($id);
    
        return $this->render('produit/produitDetail.html.twig', [
            'produit' => $produit
        ]);
    }
    

    #[Route('/produit/add', name: 'add_produit')]
    public function add(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('afficher_produit');
        }

        return $this->render('produit/add.html.twig', [
            'f' => $form->createView(),
        ]);
    }


    #[Route('/produit/modifier/{id}', name: 'modifier_produit')]
    public function modifier(Request $request, ProduitRepository $repository, $id, EntityManagerInterface $entityManager): Response
    {


        $produit = $repository->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->flush();
            return $this->redirectToRoute('afficher_produit');
        }


        return $this->render('Produit/modifier_produit.html.twig', [
            'f' => $form->createView()

        ]);
    }



    #[Route('/produit/supprimer/{id}', name: 'supprimer_produit')]
    public function supprimer_specialite(ProduitRepository  $rp, $id, Request $request, EntityManagerInterface $em)
    {
        $produit = $rp->find($id);
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('afficher_produit');
    }

    #[Route('/produit/afficher', name: 'afficher_produit')]
    public function afficher_specialite(ProduitRepository  $rp): Response
    {


        $produit = $rp->findAll();

        return $this->render('Produit/afficher_produit.html.twig', [
            'produit' => $produit
        ]);
    }
}
