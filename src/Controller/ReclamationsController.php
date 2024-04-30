<?php

namespace App\Controller;

use App\Entity\Reclamationreponse;
use App\Entity\Reclamations;
use App\Form\ReclamationsType;
use App\Form\ReclamationreponseType;
use App\Entity\Utilisateur;
use App\Repository\ReclamationreponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\ReclamationsRepository;
use vendor\consoletvs;
use App\Notification\CustomEmailNotification;


#[Route('/reclamations')]
class ReclamationsController extends AbstractController
{
    private $customEmailNotification;
    

    public function __construct(CustomEmailNotification $customEmailNotification)
    {
        $this->customEmailNotification = $customEmailNotification;
        
    }
    #[Route('/admin', name: 'app_reclamationsadmin_index', methods: ['GET'])]
    public function indexadmin(EntityManagerInterface $entityManager, ReclamationsRepository $reclamationsRepository): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamations::class)
            ->findAll();
            $sujets = $reclamationsRepository->findAllSujets();
            $statuses = $reclamationsRepository->findAllStatuses();

        return $this->render('reclamations/indexadmin.html.twig', [
            'reclamations' => $reclamations,'sujets' => $sujets,'statuses' => $statuses,
        ]);
    }
    #[Route('/', name: 'app_reclamations_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, ReclamationsRepository $reclamationsRepository): Response
    {
        
        $reclamations = $entityManager
            ->getRepository(Reclamations::class)
            ->findAll();
            $sujets = $reclamationsRepository->findAllSujets();
            $statuses = $reclamationsRepository->findAllStatuses();

        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $reclamations,'sujets' => $sujets,'statuses' => $statuses,
        ]);
    }

    #[Route('/new', name: 'app_reclamations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,\Symfony\Component\Security\Core\Security $security): Response
    {
        $reclamation = new Reclamations();
        $user = $security->getUser();
        $reclamation->setIdUser($user);
           // Définir le statut par défaut
        $reclamation->setStatus('Pending');

    // Définir la date de création par défaut
        $dateCreation = new DateTime();
        $dateCreation = date('Y-m-d H:i:s'); // Format 'année-mois-jour heure:minute:seconde'
        $reclamation->setDateCreation($dateCreation);
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $captureFile = $form->get('captureecranpath')->getData();
            
            // Check if a new file was uploaded
            if ($captureFile instanceof UploadedFile) {
                $fileName = pathinfo($captureFile->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $captureFile->guessExtension();
                $captureFile->move(
                    $this->getParameter('capture_directory'),
                    $fileName
                    
                );
                $reclamation->setCaptureecranpath($fileName);
            } else {
                // If no new file was uploaded, retain the existing file path
                $reclamation->setCaptureecranpath($reclamation->getCaptureecranpath());
            }
            $content=$reclamation->getMessage();
            $cleanedcontenu= \ConsoleTVs\Profanity\Builder::blocker($content)->filter();
            $reclamation->setMessage($cleanedcontenu);
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $receiver = $reclamation->getIdUser()->getEmail();
            $message = $reclamation->getMessage();
            $this->customEmailNotification->sendEmailNotification($receiver, '[Nouvelle réclamation]', 'Vous avez déposé une nouvelle réclamation'.$message);
            $this->customEmailNotification->sendEmailNotification("firasdhmaid@gmail.com", '[Nouvelle réclamation]', 'Une nouvelle réclamation a été ajoutée'.$message);


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
    

    
    #[Route('/admin/{id}', name: 'app_reclamationsadmin_show', methods: ['GET','POST'])]
    public function showadmin(Reclamations $reclamation,ReclamationreponseRepository $reclamationreponseRepository,EntityManagerInterface $entityManager, Request $request): Response
    {
        $reclamationreponse= new Reclamationreponse();
        $form = $this->createForm(ReclamationreponseType::class, $reclamationreponse);
        $form->handleRequest($request);
        $reclamationreponse->setIdUser($reclamation->getIdUser());
        $reclamationreponse->setIdReclamation($reclamation);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamationreponse);
            $entityManager->flush();
            $receiver = $reclamationreponse->getIdUser()->getEmail();
            $message = $reclamationreponse->getReponse();
            $this->customEmailNotification->sendEmailNotification($receiver, '[Nouvelle réponse à la réclamation]', 'L\'admin a répondu a votre réclamation:'.$message);

            return $this->redirectToRoute('app_reclamationsadmin_show', ['id'=>$reclamation->getId()], Response::HTTP_SEE_OTHER);
        }
        $reponses = $reclamationreponseRepository->findBy(['idReclamation' => $reclamation->getId()]);

        return $this->render('reclamations/showadmin.html.twig', [
            'reclamation' => $reclamation,
            'reponses' => $reponses,
            'form' => $form->createView(),
        ]);
        
    }


    #[Route('/{id}/edit', name: 'app_reclamations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

    
        if ($form->isSubmitted() && $form->isValid()) {
            $captureFile = $form->get('captureecranpath')->getData();
            
            // Check if a new file was uploaded
            if ($captureFile instanceof UploadedFile) {
                $fileName = pathinfo($captureFile->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $captureFile->guessExtension();
                $captureFile->move(
                    $this->getParameter('capture_directory'),
                    $fileName
                    
                );
                $reclamation->setCaptureecranpath($fileName);
                
            } else {
                // If no new file was uploaded, retain the existing file path
                $reclamation->setCaptureecranpath($reclamation->getCaptureecranpath());
            }
            $content=$reclamation->getMessage();
                $cleanedcontenu= \ConsoleTVs\Profanity\Builder::blocker($content)->filter();
                $reclamation->setMessage($cleanedcontenu);
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
        return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/change-status', name: 'change_reclamation_status', methods: ['POST'])]
    public function changeStatus(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager): Response
    {
        $newStatus = $request->request->get('status');

        // Check if the transition is allowed
        if ($reclamation->getStatus() === 'Pending' && ($newStatus === 'In Progress'||$newStatus === 'Resolved')) {
            // Update the status of the reclamation entity
            $reclamation->setStatus($newStatus);
        } elseif ($reclamation->getStatus() === 'In Progress' && $newStatus === 'Resolved') {
            // Update the status of the reclamation entity
            $reclamation->setStatus($newStatus);
        } elseif ($reclamation->getStatus() === 'Resolved') {
            // If the current status is 'Resolved', no transition is allowed
            // You can handle this case according to your application's requirements
            // For example, you can display a message or redirect the user
            return $this->redirectToRoute('app_reclamationsadmin_show', ['id' => $reclamation->getId()]);
        } else {
            // If the transition is not allowed, you can handle this case as well
            // For example, display an error message or redirect the user
            return $this->redirectToRoute('app_reclamationsadmin_show', ['id' => $reclamation->getId()]);
        }
    
    
        // Persister les changements dans la base de données
        $entityManager->flush();
    
        // Rediriger vers la page de détails de la réclamation
        return $this->redirectToRoute('app_reclamationsadmin_show', ['id' => $reclamation->getId()]);
    }

    #[Route('/reclamations/statistiques', name: 'app_reclamations_statistics', methods: ['GET'])]
    public function statistics(ReclamationsRepository $reclamationsRepository): Response
    {
        // Récupérer les statistiques depuis le repository
        $statistics = $reclamationsRepository->getStatistics();

        return $this->render('reclamations/statistics.html.twig', [
            'statistics' => $statistics,
        ]);
    }
}
