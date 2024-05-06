<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\Membre;
use App\Entity\Utilisateur;
use App\Repository\EquipeRepository;
use App\Repository\InvitationRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Form\InvitationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invitation')]
class InvitationController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    #[Route('/', name: 'app_invitation_index', methods: ['GET'])]
    public function index(InvitationRepository $invitationRepository,EntityManagerInterface $entityManager): Response
    {
        $invitations = $invitationRepository->findInvitationsByJoueurInviteAndStatut($this->getUser()->getId());

        return $this->render('invitation/index.html.twig', [
            'invitations' => $invitations,
        ]);
    }

    #[Route('/new/{id}', name: 'app_invitation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $id = $request->get('id');
        //$invitation = new Invitation();
        /*$form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invitation);
            $entityManager->flush();

            return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
        }**/

        return $this->render('invitation/new.html.twig', [
            'idequipe' => $id,
        ]);
    }
    #[Route('/send/{idequipe}', name: 'invitation_send', methods: ['POST'])]
    public function sendInvitation(EquipeRepository $equipeRepository,Request $request,EntityManagerInterface $entityManager): Response
    {   
        if ($request->isMethod('POST')) {
            $idequipe=$request->get('idequipe');
            $username = $request->request->get('username');
            $entityManager = $this->getDoctrine()->getManager();
            $userRepository = $entityManager->getRepository(Utilisateur::class);
            $user = $userRepository->findOneBy(['username' => $username]);
            if (!$user) {
                $this->addFlash('error', 'Utilisateur non trouvé avec le nom d\'utilisateur ' . $username);
                return $this->redirectToRoute('app_invitation_new', ['id'=>$idequipe], Response::HTTP_SEE_OTHER);
            }
            $eq=$equipeRepository->find($idequipe);
            $invitation = new Invitation();
            $invitation->setStatut("en attente");
            $invitation->setIdequipe($eq);
            $invitation->setJoueurinviteur($this->getUser());
            $invitation->setJoueurinvite($user);
            if ($user) {
                $entityManager->persist($invitation);
                $entityManager->flush();
                $emailContent = (new TemplatedEmail())
                    ->from(new Address('forhopeplay@gmail.com', 'Invitation reçue'))
                    ->to($user->getEmail())
                    ->subject('Vous avez reçu une invitation pour rejoindre une équipe')
                    ->htmlTemplate('emails/invitation.html.twig') // Template Twig pour l'e-mail d'invitation
                    ->context([
                        'user_name' => $user->getNom(),
                        'equipe_name'=>$eq->getNom(),
                    ]);

                $this->mailer->send($emailContent);
                $this->addFlash('success', 'Utilisateur invité avec succès : ' . $username);
                return $this->redirectToRoute('app_invitation_new', ['id'=>$idequipe], Response::HTTP_SEE_OTHER);
            } 
        }
    }


    #[Route('/{id}', name: 'app_invitation_show', methods: ['GET'])]
    public function show(Invitation $invitation): Response
    {
        return $this->render('invitation/show.html.twig', [
            'invitation' => $invitation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invitation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invitation $invitation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('invitation/edit.html.twig', [
            'invitation' => $invitation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invitation_delete', methods: ['POST'])]
    public function delete(Request $request, Invitation $invitation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invitation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invitation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/accept/{id}', name: 'invitation_accept', methods: ['GET','POST'])]
    public function acceptInvitation(InvitationRepository $invitationRepository,Request $request,EntityManagerInterface $entityManager): Response
    {   
       $idinvitation=$request->get('id');
       $invitation=$invitationRepository->find($idinvitation);
       $invitation->setStatut("acceptée");
       $membre=new Membre();
       $membre->setIdequipe($invitation->getIdequipe());
       $membre->setIduser($this->getUser());
       $entityManager->persist($invitation);
       $entityManager->persist($membre);
        $entityManager->flush();
       return $this->redirectToRoute('app_afficher_equipe', ['idtournoi'=>$invitation->getIdequipe()->getIdtournoi()->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/refuser/{id}', name: 'invitation_refuse', methods: ['GET','POST'])]
    public function refuserInvitation(InvitationRepository $invitationRepository,Request $request,EntityManagerInterface $entityManager): Response
    {   
       $idinvitation=$request->get('id');
       $invitation=$invitationRepository->find($idinvitation);
       $invitation->setStatut("refusée");
       $entityManager->persist($invitation);
        $entityManager->flush();
       return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
    }
}
