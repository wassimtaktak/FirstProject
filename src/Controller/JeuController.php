<?php

namespace App\Controller;

use App\Entity\Jeu;
use App\Form\JeuType;
use App\Entity\Tournoi;
use App\Repository\TournoiRepository;
use App\Repository\JeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Form\FormError;


#[Route('/jeu')]
class JeuController extends AbstractController
{
    #[Route('/', name: 'app_jeu_index', methods: ['GET'])]
    public function index(Request $request, JeuRepository $jeuRepository): Response
    {
        $searchQuery = $request->query->get('q');
        $type = $request->query->get('type');
    
        if ($searchQuery) {
            $jeus = $jeuRepository->findBySearchQuery($searchQuery);
        } elseif ($type === 'alphabetique-desc') {
            $jeus = $jeuRepository->findBy([], ['nom' => 'DESC']);
        } else {
            $jeus = $jeuRepository->findBy([], ['nom' => 'ASC']);
        }
    
        return $this->render('jeu/index.html.twig', [
            'jeus' => $jeus,
        ]);
    }


    #[Route('/new', name: 'app_jeu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, JeuRepository $jeuRepository): Response
    {
        $jeu = new Jeu();
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $form->get('nom')->getData();
            $imageFile = $form->get('imagejeu')->getData();
            if (empty($nom)) {
                $this->addFlash('error', 'Le champ "Nom" ne peut pas être vide.');
                return $this->redirectToRoute('app_jeu_new');
            }
            if (empty($imageFile)) {
                $this->addFlash('error', 'Le champ "Image du jeu" ne peut pas être vide.');
                return $this->redirectToRoute('app_jeu_new');
            }
            $existingJeu = $jeuRepository->findOneBy(['nom' => $nom]);
            if ($existingJeu) {
                $this->addFlash('error', 'Le nom du jeu existe déjà.');
                return $this->redirectToRoute('app_jeu_new');
            }
    
            $file = $form->get('imagejeu')->getData();
            if ($file) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                    $jeu->setImagejeu($fileName);
                } catch (FileException $e) {
                }
            }
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jeu);
            $entityManager->flush();
            $jeuRepository->add($jeu, true);
    
            return $this->redirectToRoute('app_jeu_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('jeu/new.html.twig', [
            'jeu' => $jeu,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_jeu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jeu $jeu, JeuRepository $jeuRepository): Response
    {
        $imageFileName=$jeu->getImagejeu();
        $form = $this->createForm(JeuType::class, $jeu,['imagejeu_default' => $imageFileName]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $form->get('nom')->getData();
            $imageFile = $form->get('imagejeu')->getData();
            
          
            if (!$imageFile) {
                $this->addFlash('error', 'Le champ "Image du jeu" ne peut pas être vide.');
                return $this->redirectToRoute('app_jeu_edit', ['id' => $jeu->getId()]);
            }
         
            $existingJeu = $jeuRepository->findOneBy(['nom' => $nom]);
            if ($existingJeu && $existingJeu->getId() !== $jeu->getId()) {
                $this->addFlash('error', 'Le nom du jeu existe déjà.');
                return $this->redirectToRoute('app_jeu_edit', ['id' => $jeu->getId()]);
            }
            
           
            if (empty($nom)) {
                $this->addFlash('error', 'Le champ "Nom" ne peut pas être vide.');
                return $this->redirectToRoute('app_jeu_edit', ['id' => $jeu->getId()]);
            }
          
            $file = $form->get('imagejeu')->getData();
            if ($file!=$imageFileName) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                    $jeu->setImagejeu($fileName);
                } catch (FileException $e) {
                 
                }
            }
          
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jeu);
            $entityManager->flush();
            $jeuRepository->add($jeu, true);
          
       
            return $this->redirectToRoute('app_jeu_index', [], Response::HTTP_SEE_OTHER);
        }
    
     
        return $this->renderForm('jeu/edit.html.twig', [
            'jeu' => $jeu,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_jeu_delete', methods: ['POST'])]
    public function delete(Request $request, Jeu $jeu, EntityManagerInterface $entityManager, TournoiRepository $tournoiRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jeu->getId(), $request->request->get('_token'))) {
            // Check if the game is used in a tournament
            if ($tournoiRepository->jeuTournoi($jeu->getId())) {
                // Display error message if the game is used in a tournament
                $this->addFlash('error', 'This game has already been used in a tournament.');
                return $this->redirectToRoute('app_jeu_index', [], Response::HTTP_SEE_OTHER);
            }
    
            // Proceed with deletion if the game is not used in a tournament
            $entityManager->remove($jeu);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_jeu_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
