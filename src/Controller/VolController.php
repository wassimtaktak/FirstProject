<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VolRepository;

class VolController extends AbstractController
{
    #[Route('vol/all', name: 'app_vol')]
    public function findallbooks(VolRepository $volrepository): Response
    {   
        $vols = $volrepository->findAll();
        return $this->render('vol/show.html.twig', ['vols' => $vols,]);
    }
}
