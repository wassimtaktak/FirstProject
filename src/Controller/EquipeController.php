<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\TournoiRepository;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipe')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $equipes = $entityManager
            ->getRepository(Equipe::class)
            ->findAll();

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes,
        ]);
    }

    #[Route('/new/{id}', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new($id, TournoiRepository $tournoiRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournoi = $tournoiRepository->find($id);

        if (!$tournoi) {
            throw $this->createNotFoundException('Tournoi non trouvé');
        }

        $equipe = new Equipe();
        $equipe->setIdtournoi($tournoi); 
        $equipe->setDisponibilite(false);


        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($equipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_show', ['id' => $id]);
        }

        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET', 'POST'])]
    public function show(EquipeRepository $equipeRepository,Request $request,EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('id'); // Récupérer l'ID du tournoi depuis l'URL
        // Recherche des équipes par ID de tournoi (utilisation de l'ID de l'URL)
        $equipes = $equipeRepository->findByIdTournoi($idTournoi);

        // équipe statique par son ID li bch neditiha ba3 nbadalha b session
        $equipe = $equipeRepository->find(28);
        // forumulaire  ta3 edit equipe ta3 li mlogi
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_show', ['id' => $idTournoi], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('equipe/index.html.twig', 
        [ 'idtournoi'=>$idTournoi,'equipes' => $equipes,'equipe' => $equipe,'form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
