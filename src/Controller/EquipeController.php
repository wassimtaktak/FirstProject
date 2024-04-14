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
    public function show(EquipeRepository $equipeRepository, TournoiRepository $tournoiRepository,Request $request,EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('id'); 
        $equipes = $equipeRepository->findByIdTournoi($idTournoi);//afficher equipet tournoi
        $tournoi = $tournoiRepository->find($idTournoi);
        $equipesComplet = count($equipes) == $tournoi->getNbrequipe();
        return $this->render('equipe/index.html.twig', 
        [ 'idtournoi'=>$idTournoi,'equipes' => $equipes,'equipesComplet' => $equipesComplet,]);
    }

    #[Route('/{id}/edit/{idtournoi}', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);
        $idTournoi = $request->get('idtournoi'); 

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_show', ['id' => $idTournoi], Response::HTTP_SEE_OTHER);
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
