<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\Persistence\ManagerRegistry;
class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
    #[Route('reservation/new/{id}', name: 'app_reservation_new')]
    public function new($id,ManagerRegistry $doctrine,Request $request): Response
    {   
        $entityManager=$doctrine->getManager();
        $vol = $entityManager->getRepository(Reservation::class)->find($id);
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $entityManager->persist($reservation);
            $entityManager->persist($vol);
            $entityManager->flush();

            return $this->redirectToRoute('app_vol');
        }

        return $this->renderForm('reservation/new.html.twig', ['reservation' => $reservation,'form' => $form,]);
    }
}
