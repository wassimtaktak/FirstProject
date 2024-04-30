<?php

namespace App\Controller;

use App\Entity\Reclamationreponse;
use App\Form\ReclamationreponseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;

#[Route('/reclamationreponse')]
class ReclamationreponseController extends AbstractController
{
    #[Route('/', name: 'app_reclamationreponse_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamationreponses = $entityManager
            ->getRepository(Reclamationreponse::class)
            ->findAll();

        return $this->render('reclamationreponse/index.html.twig', [
            'reclamationreponses' => $reclamationreponses,
        ]);
    }

    #[Route('/new', name: 'app_reclamationreponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamationreponse = new Reclamationreponse();
        $form = $this->createForm(ReclamationreponseType::class, $reclamationreponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamationreponse);
            $entityManager->flush();
            

            return $this->redirectToRoute('app_reclamationreponse_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('reclamationreponse/new.html.twig', [
            'reclamationreponse' => $reclamationreponse,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}', name: 'app_reclamationreponse_show', methods: ['GET'])]
    public function show(Reclamationreponse $reclamationreponse): Response
    {


        
        return $this->render('reclamationreponse/show.html.twig', [
            'reclamationreponse' => $reclamationreponse,
            
        ]);
    }

    #[Route('/show-notification', name: 'notification_show', methods: ['GET'])]
    
    public function showNotification(): Response
    {
        
        // Récupérer les données nécessaires pour la notification
        $notificationData = [
            'title' => 'Nouvelle notification',
            'content' => 'Vous avez reçu un nouveau message de notification.',
            'timestamp' => new \DateTime(),
            'sender' => 'John Doe',
            'recipient' => 'Jane Smith',
            // Autres données spécifiques à la notification...
        ];

        return $this->render('reclamationreponse/notification.html.twig', [
            'notificationData' => $notificationData,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_reclamationreponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamationreponse $reclamationreponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationreponseType::class, $reclamationreponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamationsadmin_show', ["id"=>$reclamationreponse->getIdReclamation()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamationreponse/edit.html.twig', [
            'reclamationreponse' => $reclamationreponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamationreponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamationreponse $reclamationreponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamationreponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamationreponse);
            $entityManager->flush();
        }
        $id = $reclamationreponse->getIdReclamation()->getId();

        return $this->redirectToRoute('app_reclamationsadmin_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
    }
}
