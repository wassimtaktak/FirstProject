<?php

namespace App\Controller;

use App\Entity\Reclamations;
use App\Form\ReclamationsType;
use App\Repository\ReclamationreponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reclamations')]
class ReclamationsController extends AbstractController
{
    #[Route('/', name: 'app_reclamations_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamations::class)
            ->findAll();

        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/new', name: 'app_reclamations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamations();
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamations/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamations_show', methods: ['GET'])]
    public function show(Reclamations $reclamation,ReclamationreponseRepository $reclamationreponseRepository): Response
    {
        $reponses = $reclamationreponseRepository->findBy(['idReclamation' => $reclamation->getId()]);

        return $this->render('reclamations/show.html.twig', [
            'reclamation' => $reclamation,
            'reponses' => $reponses
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamations/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_reclamations_delete')]
    public function delete(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager,int $id): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            echo $reclamation->getId();
            $entityManager->remove($reclamation);
            $entityManager->flush();
           
        }
        echo '<script>alert("Welcome to Geeks for Geeks")</script>';
        echo "test";

        return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
    }
}
