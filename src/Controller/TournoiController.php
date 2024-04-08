<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\TournoiType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tournoi')]
class TournoiController extends AbstractController
{
    #[Route('/', name: 'app_tournoi_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tournois = $entityManager
            ->getRepository(Tournoi::class)
            ->findAll();

        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournois,
        ]);
    }

    #[Route('/new', name: 'app_tournoi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $tournoi->getJour();
            $currentTime = new \DateTime();
            if ($date < $currentTime) {
                $this->addFlash('error', 'La date est déjà passée.');
                return $this->redirectToRoute('app_tournoi_new');
            }

            $heure = $tournoi->getTempsdeb();
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $heure)) {
                $this->addFlash('error', 'Le format du temps doit être sous forme de HH:mm.');
                return $this->redirectToRoute('app_tournoi_new');
            }

            $entityManager->persist($tournoi);
            $entityManager->flush();
            //$this->addFlash('success', 'Le tournoi a été créé avec succès.');

            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournoi/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tournoi_show', methods: ['GET'])]
    public function show(Tournoi $tournoi): Response
    {
        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tournoi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $tournoi->getJour();
            $currentTime = new \DateTime();
            if ($date < $currentTime) {
                $this->addFlash('error', 'La date est déjà passée.');
                
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }

            $heure = $tournoi->getTempsdeb();
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $heure)) {
                $this->addFlash('error', 'Le format du temps doit être sous forme de HH:mm.');
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournoi/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tournoi_delete', methods: ['POST'])]
    public function delete(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournoi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
    }
}
