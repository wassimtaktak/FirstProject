<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Utilisateur;
use App\Form\ForumType;
use App\Form\PostType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

#[Route('/forum')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'app_forum_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $forums = $entityManager
            ->getRepository(Forum::class)
            ->findAll();

        return $this->render('forum/index.html.twig', [
            'forums' => $forums,
        ]);
    }
    #[Route('/admin/', name: 'app_forumadmin_index', methods: ['GET'])]
    public function indexadmin(EntityManagerInterface $entityManager): Response
    {
        $forums = $entityManager
            ->getRepository(Forum::class)
            ->findAll();

        return $this->render('forum/indexadmin.html.twig', [
            'forums' => $forums,
        ]);
    }

    #[Route('/new', name: 'app_forum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forum/new.html.twig', [
            'forum' => $forum,
            'form' => $form,
        ]);
    }
    #[Route('/new/admin', name: 'app_forumadmin_new', methods: ['GET', 'POST'])]
    public function newadmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forum/newadmin.html.twig', [
            'forum' => $forum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forum_show', methods: ['GET','POST'])]
    public function show(int $id, Forum $forum, EntityManagerInterface $entityManager, Request $request, ForumRepository $forumRepository, TokenGeneratorInterface $tokenGenerator): Response
{
    $postData = "";
    $post = new Post();
    $forum = $forumRepository->find($id);
    $post->setIdForum($forum);

   

    if ($request->isMethod('POST')) {
        
        $postData = $request->request->get('post_content');
        $post->setMessage($postData);
        $post->setNbLike(0);
        $user = $entityManager->getRepository(Utilisateur::class)->find(5);
        $post->setIdUser($user);
        $entityManager->persist($post);
        $entityManager->flush();

        
        return new RedirectResponse($this->generateUrl('app_forum_show', ['id' => $id]));
    }

    $posts = $entityManager
        ->getRepository(Post::class)
        ->findBy(['idForum' => $id]);

        $postvide = new Post();
        $postvide->setMessage("");
    return $this->render('post/index.html.twig', [
        'posts' => $posts,
        'forum' => $forum,
        'post' => $postvide
    ]);
}

#[Route('/{id}/admin', name: 'app_forumadmin_show', methods: ['GET','POST'])]
    public function showadmin(int $id, Forum $forum, EntityManagerInterface $entityManager, Request $request, ForumRepository $forumRepository, TokenGeneratorInterface $tokenGenerator): Response
{
    $postData = "";
    $post = new Post();
    $forum = $forumRepository->find($id);
    $post->setIdForum($forum);

   

    if ($request->isMethod('POST')) {
        
        $postData = $request->request->get('post_content');
        $post->setMessage($postData);
        $post->setNbLike(0);
        $user = $entityManager->getRepository(Utilisateur::class)->find(5);
        $post->setIdUser($user);
        $entityManager->persist($post);
        $entityManager->flush();

        
        return new RedirectResponse($this->generateUrl('app_forumadmin_show', ['id' => $id]));
    }

    $posts = $entityManager
        ->getRepository(Post::class)
        ->findBy(['idForum' => $id]);

        $postvide = new Post();
        $postvide->setMessage("");
    return $this->render('post/indexadmin.html.twig', [
        'posts' => $posts,
        'forum' => $forum,
        'post' => $postvide
    ]);
}
    

    #[Route('/{id}/edit', name: 'app_forum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_forum_delete', methods: ['POST'])]
public function delete(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->request->get('_token'))) {
        $entityManager->remove($forum);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
}
}
