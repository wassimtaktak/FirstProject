<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tournoi;
use App\Repository\TournoiRepository;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, TournoiRepository $tournoiRepository): Response
    {
        $tournois = $tournoiRepository->SortedTournois();
        return $this->render('home/index.html.twig', [
            'tournois' => $tournois,
        ]);
    }
}