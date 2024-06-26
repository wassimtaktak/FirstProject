<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Form\UtilisateurTypeNoRole;
use App\Form\UtilisateurTypenopass;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/all', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, UtilisateurRepository $repo): Response
    {
        $utilisateurs = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();
        $adminCount = $repo->countUsersByRoleId(2);
        $userCount = $repo->countUsersByRoleId(1);
        $organisateurCount = $repo->countUsersByRoleId(3);
        $rapporteurCount = $repo->countUsersByRoleId(4);
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'adminCount' => $adminCount,
            'userCount' => $userCount,
            'organisateurCount' => $organisateurCount,
            'rapporteurCount' => $rapporteurCount,
        ]);


    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $utilisateur->setPassword(
                $userPasswordHasher->encodePassword(
                    $utilisateur,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(\Symfony\Component\Security\Core\Security $security): Response
    {
        $user = $security->getUser();


        $userId = null;


        if ($user) {
            $userId = $user->getId();
        }

        return $this->render('utilisateur/show.html.twig', [
            'userId' => $userId,
        ]);
    }


    #[Route('/{id}/editUser', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurTypenopass::class, $utilisateur);
        $form->handleRequest($request);
        $utilisateur = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/editprofile', name: 'app_utilisateur_editprofile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurTypeNoRole::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            return $this->redirectToRoute('app_utilisateur_show', ['id' => $utilisateur->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('utilisateur/editProfile.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
    #[Route('/delete/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

}
