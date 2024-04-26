<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Entity\jeu;
use App\Repository\TournoiRepository;
use App\Repository\JeuRepository;
use App\Form\TournoiType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/tournoi')]
class TournoiController extends AbstractController
{
    #[Route('/', name: 'app_tournoi_index', methods: ['GET'])]
    public function index(Request $request, TournoiRepository $tournoiRepository, JeuRepository $jeuRepository,PaginatorInterface $paginator): Response
    {
        $gameId = $request->query->get('game');
        $jeux = $jeuRepository->findAll();
        
        if ($gameId) {
            $tournois = $tournoiRepository->filteredByGames($gameId);
        } else {
            $tournois = $tournoiRepository->findFilteredAndSortedTournois($request->query->get('type'));
        }
        $pagination = $paginator->paginate(
            $tournois,
            $request->query->getInt('page', 1), 
            3
        );
        return $this->render('tournoi/index.html.twig', [
            'pagination' => $pagination,
            'jeux' => $jeux,
        ]);
    }
    #[Route('/statistiques', name: 'app_statistiques', methods: ['GET'])]
    public function statistiques(TournoiRepository $tournoiRepository): Response
    {
        $tournoisByJeu= $tournoiRepository->countTournoisByJeu();
        return $this->render('tournoi/statistiques.html.twig', [
            'tournoisByJeu' => $tournoisByJeu,
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
            $nom = $form->get('name')->getData();
            $regles = $form->get('regles')->getData();
            $heure = $tournoi->getTempsdeb();
            $jpt = $form->get('jpt')->getData();
            $currentTime = new \DateTime();
            if (empty($nom)) {
                $this->addFlash('error', 'Le champ "Nom" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_new');
            }
            if (empty($regles)) {
                $this->addFlash('error', 'Le champ "Régles" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_new');
            }
            if (empty($date)) {
                $this->addFlash('error', 'Le champ "Date" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_new');
            }
            if ($date < $currentTime) {
                $this->addFlash('error', 'La date est déjà passée.');
                return $this->redirectToRoute('app_tournoi_new');
            }
            $prix = $form->get('prize')->getData();
            if (empty($prix)) {
                $this->addFlash('error', 'Le champ "Prix" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_new');
            }
            if (empty($heure)) {
                $this->addFlash('error', 'Le champ "Date début" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_new');
            }
           if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $heure)) {
                $this->addFlash('error', 'Le format du temps doit être sous forme de HH:mm.');
                return $this->redirectToRoute('app_tournoi_new');
            }
            if (empty($jpt)) {
                $this->addFlash('error', 'Le champ "joueur par équipe" ne peut pas être vide.');
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
            $nom = $form->get('name')->getData();
            $regles = $form->get('regles')->getData();
            $heure = $tournoi->getTempsdeb();
            $jpt = $form->get('jpt')->getData();
            $currentTime = new \DateTime();
            if (empty($nom)) {
                $this->addFlash('error', 'Le champ "Nom" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }
            if (empty($regles)) {
                $this->addFlash('error', 'Le champ "Régles" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }
            if (empty($date)) {
                $this->addFlash('error', 'Le champ "Date" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }
            if ($date < $currentTime) {
                $this->addFlash('error', 'La date est déjà passée.');
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }
            $prix = $form->get('prize')->getData();
            if (empty($prix)) {
                $this->addFlash('error', 'Le champ "Prix" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }
            if (empty($heure)) {
                $this->addFlash('error', 'Le champ "Date début" ne peut pas être vide.');
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $heure)) {
                $this->addFlash('error', 'Le format du temps doit être sous forme de HH:mm.');
                return $this->redirectToRoute('app_tournoi_edit', ['id' => $tournoi->getId()]);
            }
            if (empty($jpt)) {
                $this->addFlash('error', 'Le champ "joueur par équipe" ne peut pas être vide.');
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
