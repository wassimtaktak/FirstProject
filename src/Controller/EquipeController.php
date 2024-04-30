<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Membre;
use App\Form\EquipeType;
use App\Repository\TournoiRepository;
use App\Repository\MembreRepository;
use App\Repository\EquipeRepository;
use App\Repository\PartieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
    //page taffichi PARTIE SCORE 
    #[Route('/afficher/{idtournoi}', name: 'app_afficher_equipe', methods: ['GET'])]
    public function afficher(TournoiRepository $tournoiRepository,MembreRepository $membreRepository,EquipeRepository $equipeRepository,Request $request,PartieRepository $partieRepository, EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('idtournoi');
        $tournoi = $tournoiRepository->find($idTournoi);
        $userId = $this->getUser()->getId(); 
        $equipe = $equipeRepository->getMyTeamForTournament($userId, $idTournoi);
        $existpartie = null;
        if ($equipe !== null) {
            $existpartie = $partieRepository->EquipeInParties($equipe->getId());
            $membres=$membreRepository->findMembresByEquipeId($equipe->getId());
            $membre=$membreRepository->findMembreByEquipeAndUserId($equipe->getId(),$userId);
        }    
        return $this->render('equipe/afficher.html.twig', [
            'membre'=>$membre,
            'membres'=>$membres,
            'equipe' => $equipe,
            'idtournoi'=>$idTournoi,
            'existpartie'=>$existpartie,
            'tournoi'=>$tournoi,
        ]);
    }
    //
    #[Route('/myteam/{idtournoi}', name: 'app_myteam_equipe', methods: ['GET'])]
    public function monequipe(EquipeRepository $equipeRepository,Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('idtournoi'); 
        $equipe=$equipeRepository->getMyTeamForTournament(14,$idTournoi);
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
            'idtournoi'=>$idTournoi
        ]);
    }
    #[Route('/new/{id}', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new($id,EquipeRepository $equipeRepository, TournoiRepository $tournoiRepository, Request $request, EntityManagerInterface $entityManager): Response
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
            //exist nom
            if($equipeRepository->isTeamNameExistsForTournament($equipe->getNom(),$tournoi->getId()))
                {
                    $this->addFlash('error', 'Ce nom est déja utilisé');
                    return $this->redirectToRoute('app_equipe_new', ['id'=>$tournoi->getId()], Response::HTTP_SEE_OTHER);
                }
            $file = $form->get('imageequipe')->getData();
            if ($file) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                    $equipe->setImageequipe($fileName);
                } catch (FileException $e) {
                }
            }
            $membre=new Membre();
            $membre->setIdequipe($equipe);
            $membre->setIduser($this->getUser());
            $entityManager->persist($membre);
            $entityManager->persist($equipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_show', ['id' => $id]);
        }

        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }
    #[Route('/generatepdf', name: 'app_generer_certificat', methods: ['GET'])]
    public function generatecertificatpdf(Request $request): Response
    {   
        $user = $this->getUser();
        $winnerName = $user->getNom() . ' ' . $user->getPrenom();
        // Chemin vers l'image de fond du certificat
        $backgroundImage = realpath($this->getParameter('kernel.project_dir') . '/public/img/certificat.png');

        // Créer une instance de TCPDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Paramètres du document
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Certificate of Achievement');
        $pdf->SetSubject('Certificate');
        $pdf->SetKeywords('Certificate, Achievement');

        // Ajouter une page
        $pdf->AddPage();


        // Ajouter l'image de fond
        $pdf->Image($backgroundImage, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), '', '', '', false, 300, '', false, false, 0);


        // Ajouter le nom du gagnant

        $pdf->SetFont('helvetica', 'B', 24);
        $pdf->SetTextColor(0, 0, 0); // Noir
        $pdf->SetXY(5, 145); // Position du texte
        $pdf->Cell(0, 0, $winnerName, 0, false, 'C', 0, '', 0, false, 'M', 'M');

        // Enregistrer le PDF
        $outputFile = $this->getParameter('kernel.project_dir') . '/public/certificates/Certificate.pdf';
        $pdf->Output($outputFile, 'F');

        // Réponse HTTP avec le fichier PDF généré
        return $this->file($outputFile, 'Certificate.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }
    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET', 'POST'])]
    public function show(EquipeRepository $equipeRepository, TournoiRepository $tournoiRepository,Request $request,EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('id');
        $userId = $this->getUser()->getId(); //id l user li mconecty
        $equipe = $equipeRepository->getMyTeamForTournament($userId, $idTournoi);//equipe li mconecty
        $equipes = $equipeRepository->findByIdTournoi($idTournoi);//afficher equipet tournoi kol
        $tournoi = $tournoiRepository->find($idTournoi);
        $equipesComplet = count($equipes) == $tournoi->getNbrequipe();
        return $this->render('equipe/index.html.twig', 
        [ 'idtournoi'=>$idTournoi,'equipes' => $equipes,'equipesComplet' => $equipesComplet,'myteam'=>$equipe]);
    }
    #[Route('/{id}/edit/{idtournoi}', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {   
        $imageFileName = $equipe->getImageequipe();
        $form = $this->createForm(EquipeType::class, $equipe, [
            'imageequipe_default' => $imageFileName,
        ]);
        $form->handleRequest($request);
        $idTournoi = $request->get('idtournoi'); 

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $file = $form->get('imageequipe')->getData();
            if ($file!=$imageFileName) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                    $equipe->setImageequipe($fileName);
                } catch (FileException $e) {
                    dump($file);
                }
            } 

            $entityManager->flush();
            return $this->redirectToRoute('app_equipe_show', ['id' => $idTournoi], Response::HTTP_SEE_OTHER);
        }

        
        return $this->render('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('delete/{id}/{idtournoi}', name: 'app_equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {   
        $idTournoi = $request->get('idtournoi'); 
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipe_show',  ['id'=>$idTournoi], Response::HTTP_SEE_OTHER);
    }
    #[Route('/classement/{id}', name: 'app_equipe_classement', methods: ['GET'])]
    public function classement(Request $request,EquipeRepository $equipeRepository,PartieRepository $partieRepository,EntityManagerInterface $entityManager): Response
    {
        
        $idTournoi = $request->get('id'); 
        $equipes = $equipeRepository->findByIdTournoi($idTournoi);
        $monequipe = $equipeRepository->getMyTeamForTournament($this->getUser()->getId(), $idTournoi);
        $monequipegagne = (count($equipes) > 0 && $monequipe !== null) ? ($equipes[0]->getId() === $monequipe->getId()) : false;
        $isFinaleExist = $partieRepository->checkPartiesExistForPhase("finale", $idTournoi);
        $existunupdated=$partieRepository->hasUnupdatedParties($idTournoi);
        return $this->render('equipe/Classement.html.twig', [
            'gagnant'=>$monequipegagne,
            'existunupdated'=>$existunupdated,
            'existefinale'=>$isFinaleExist,
            'equipes' => $equipes,
            'idtournoi'=>$idTournoi,
        ]);
    }
    
    
}
