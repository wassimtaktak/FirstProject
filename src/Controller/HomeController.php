<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tournoi;
use App\Repository\TournoiRepository;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager,TournoiRepository $tournoiRepository,ProduitRepository $produitRepository): Response
    {
        $tournois = $tournoiRepository->SortedTournois();
        $produits =$produitRepository->findAll();
        return $this->render('home/index.html.twig', [
            'tournois' => $tournois,
            'produits' =>$produits,
        ]);
    }
}
