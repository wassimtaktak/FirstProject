<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Form\PartieType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PartieRepository;
use App\Repository\TournoiRepository;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partie')]
class PartieController extends AbstractController
{
    #[Route('/', name: 'app_partie_index', methods: ['GET'])]
    public function index(PartieRepository $partieRepository,Request $request,EquipeRepository $equipeRepository, TournoiRepository $tournoiRepository,EntityManagerInterface $entityManager): Response
    {
        $equipesparticipantes = $partieRepository->findEquipesGagnantesByPhase(1, "quart de finale");

        return $this->render('partie/index.html.twig', [
            'parties' => $equipesparticipantes,
        ]);
    }

    #[Route('/new/{id}', name: 'app_partie_new', methods: ['GET', 'POST'])]
    public function new(PartieRepository $partieRepository,TournoiRepository $tournoiRepository,Request $request, EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('id');
        $isQuartdeFinaleExist = $partieRepository->checkPartiesExistForPhase("quart de finale", $idTournoi);
        $isDemiFinaleExist = $partieRepository->checkPartiesExistForPhase("demi finale", $idTournoi);
        $isFinaleExist = $partieRepository->checkPartiesExistForPhase("finale", $idTournoi);
        $tournoi = $tournoiRepository->find($idTournoi);
        return $this->renderForm('partie/new.html.twig', [
            'tournoi'=>$tournoi,
            'idtournoi'=>$idTournoi,
            'existeQuart'=>$isQuartdeFinaleExist,
            'existedemi'=>$isDemiFinaleExist,
            'existefinale'=>$isFinaleExist,
        ]);
    }
    //page fiha partiet tournoi spécifique
    #[Route('/{id}', name: 'app_partie_show', methods: ['GET'])]
    public function show(PartieRepository $partieRepository,Request $request,EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('id');
        $parties = $partieRepository->findByIdTournoi($idTournoi);
        $existunupdated=$partieRepository->hasUnupdatedParties($idTournoi);
        return $this->render('partie/index.html.twig', [
            'existunupdated'=>$existunupdated,
            'idtournoi'=>$idTournoi,
            'parties' => $parties,
        ]);
    }
    //page taffichi PARTIE SCORE 
    #[Route('/afficher/{id}', name: 'app_afficher_partie', methods: ['GET'])]
    public function afficher(Partie $partie): Response
    {
        return $this->render('partie/show.html.twig', [
            'partie' => $partie,
        ]);
    }
    //page  modifier
    #[Route('/{id}/edit/{idtournament}', name: 'app_partie_edit', methods: ['GET', 'POST'])]
    public function edit(EquipeRepository $equipeRepository,Request $request, Partie $partie, EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('idtournament');
        $form = $this->createForm(PartieType::class, $partie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $scoreEquipe1 = $partie->getScoreequipe1();
            $scoreEquipe2 = $partie->getScoreequipe2();
            if($scoreEquipe1==$scoreEquipe2){
                $this->addFlash('error', 'Une partie ne peut pas se terminer par un score égal.');
                return $this->redirectToRoute('app_partie_edit', ['id'=>$partie->getId(),'idtournament'=>$idTournoi], Response::HTTP_SEE_OTHER);
            }
            $partie->setUpdated(true);
            $equipeGagnanteId = ($scoreEquipe1 > $scoreEquipe2) ? $partie->getEquipe1id()->getId() : $partie->getEquipe2id()->getId();
            $equipeGagnante = $equipeRepository->find($equipeGagnanteId);
            if ($equipeGagnante) {
                $equipeGagnante->setPoints($equipeGagnante->getPoints() + 3);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_partie_show', ['id'=>$idTournoi], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partie/edit.html.twig', [
            'partie' => $partie,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_partie_delete', methods: ['POST'])]
    public function delete(Request $request, Partie $partie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($partie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_partie_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/generer/quart/{id}', name: 'generer_partie', methods: ['GET', 'POST'])]
    public function genererPartie(Request $request,EquipeRepository $equipeRepository, TournoiRepository $tournoiRepository,EntityManagerInterface $entityManager)
    {
        $idTournoi = $request->get('id');
        $nombreEquipes = 8; 
        try {
            $equipesparticipantes = $equipeRepository->findByIdTournoi($idTournoi);
            $parties = [];
            $tournoi = $tournoiRepository->find($idTournoi);
            for ($i = 0; $i < $nombreEquipes / 2; $i++) 
            {
                $equipe1 = $equipesparticipantes[$i * 2];
                $equipe2 = $equipesparticipantes[$i * 2 + 1];
                $partie = new Partie();
                $partie->setIdtournoi($tournoi);
                $partie->setEquipe1id($equipe1);
                $partie->setEquipe2id($equipe2);
                $partie->setScoreequipe1(0);
                $partie->setScoreequipe2(0);
                $partie->setPhase("quart de finale");
                $partie->setUpdated(false);
                $parties[] = $partie;
            }

            foreach ($parties as $partie) {
                $entityManager->persist($partie);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_partie_show', ['id' =>$idTournoi ]);
        } catch (Exception $e) {
        }
    }
    #[Route('/generer/demi/{id}', name: 'generer_partie_demi', methods: ['GET', 'POST'])]
    public function genererPartiedemi(PartieRepository $partieRepository,Request $request,EquipeRepository $equipeRepository, TournoiRepository $tournoiRepository,EntityManagerInterface $entityManager)
    {
        $idTournoi = $request->get('id');
        $nombreEquipes = 4; 
        try {
            $isQuartDeFinaleExist = $partieRepository->checkPartiesExistForPhase("quart de finale", $idTournoi);

            if (!$isQuartDeFinaleExist) {
                $equipesparticipantes = $equipeRepository->findByIdTournoi($idTournoi);// Traja3li les équipes  tournoi -> Equipe
            } else {
                $equipesparticipantes = $partieRepository->findEquipesGagnantesByPhase($idTournoi, "quart de finale"); //traja3li les id ta3 equipe ->integer
            }
    
            $parties = [];
            $tournoi = $tournoiRepository->find($idTournoi);
            for ($i = 0; $i < $nombreEquipes / 2; $i++) 
            {
                $equipe1Id = $equipesparticipantes[$i * 2];
                $equipe1 = $equipeRepository->find($equipe1Id); 
                $equipe2Id = $equipesparticipantes[$i * 2 + 1];
                $equipe2 = $equipeRepository->find($equipe2Id);
                $partie = new Partie();
                $partie->setIdtournoi($tournoi);
                $partie->setEquipe1id($equipe1);
                $partie->setEquipe2id($equipe2);
                $partie->setScoreequipe1(0);
                $partie->setScoreequipe2(0);
                $partie->setPhase("Demi finale");
                $partie->setUpdated(false);
                $parties[] = $partie;
            }

            foreach ($parties as $partie) {
                $entityManager->persist($partie);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_partie_show', ['id' =>$idTournoi ]);
        } catch (Exception $e) {
        }
    }
    #[Route('/generer/finale/{id}', name: 'generer_partie_finale', methods: ['GET', 'POST'])]
    public function genererPartiefinale(PartieRepository $partieRepository,Request $request,EquipeRepository $equipeRepository, TournoiRepository $tournoiRepository,EntityManagerInterface $entityManager)
    {
        $idTournoi = $request->get('id');
        $nombreEquipes = 2; 
        try {
            $isDemiFinaleExist = $partieRepository->checkPartiesExistForPhase("demi finale", $idTournoi);
            if (!$isDemiFinaleExist) {
                $equipesparticipantes = $equipeRepository->findByIdTournoi($idTournoi);// Traja3li les équipes  tournoi -> Equipe
            } else {
                $equipesparticipantes = $partieRepository->findEquipesGagnantesByPhase($idTournoi, "demi finale"); //traja3li les id ta3 equipe ->integer
            }
    
            $parties = [];
            $tournoi = $tournoiRepository->find($idTournoi);
            for ($i = 0; $i < $nombreEquipes / 2; $i++) 
            {
                $equipe1Id = $equipesparticipantes[$i * 2];
                $equipe1 = $equipeRepository->find($equipe1Id); 
                $equipe2Id = $equipesparticipantes[$i * 2 + 1];
                $equipe2 = $equipeRepository->find($equipe2Id);
                $partie = new Partie();
                $partie->setIdtournoi($tournoi);
                $partie->setEquipe1id($equipe1);
                $partie->setEquipe2id($equipe2);
                $partie->setScoreequipe1(0);
                $partie->setScoreequipe2(0);
                $partie->setPhase("finale");
                $partie->setUpdated(false);
                $parties[] = $partie;
            }

            foreach ($parties as $partie) {
                $entityManager->persist($partie);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_partie_show', ['id' =>$idTournoi ]);
        } catch (Exception $e) {
        }
    }
}
