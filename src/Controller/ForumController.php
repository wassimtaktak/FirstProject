<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Utilisateur;
use App\Form\ForumType;
use App\Form\PostType;
use App\Repository\ForumRepository;
use App\Repository\PostsRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;

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
            $notifier = NotifierFactory::create();
        $notification =
                (new Notification())
            ->setTitle('Nouveau forum ajouté')
            ->setBody('nouveau forum ajouté portant le nom : '.$forum->getSujet())
            ->setIcon(__DIR__.'../../public/img/forumicon.png');
            
        $notifier->send($notification);

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
    public function show(int $id, Forum $forum, EntityManagerInterface $entityManager, Request $request, ForumRepository $forumRepository, TokenGeneratorInterface $tokenGenerator, PostsRepository $PostsRepository): Response
{
    $postData = "";
    $post = new Post();
    $forum = $forumRepository->find($id);
    $post->setIdForum($forum);
    if ($request->isMethod('POST')) {
        
        $postData = $request->request->get('post_content');
        $post->setMessage($postData);
        $post->setNbLike(0);
        $post->setDatePost(date('Y-m-d H:i:s'));
        $user = $this->getUser();
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



    // Pass the list of users to the template
    return $this->render('post/index.html.twig', [
        'posts' => $posts,
        'forum' => $forum,
        'post' => $postvide,
        
    ]);
}
#[Route('/admin/statistics', name: 'app_forum_statistics', methods: ['GET','POST'])]
public function admin_statistics(PostsRepository $PostsRepository,PostRepository $PostRepository)
{

    // Fetch list of users who have written posts for this forum
    $topLikedPosts = $PostRepository->findTopLikedallPosts(5);
    $userData = $PostsRepository->countPostsPerUser();
    return $this->render('post/statistics.html.twig', [
        'userData' => $userData,
        'topLikedPosts' => $topLikedPosts
    ]);
}
#[Route('/{id}/admin/statistics', name: 'app_forumadmin_statistics', methods: ['GET','POST'])]
public function statistics_perforum(int $id,PostsRepository $PostsRepository,PostRepository $PostRepository,ForumRepository $forumRepository)
{
    // Fetch the forum
    $forum = $forumRepository->find($id);
        
    // Fetch users and count of their posts for the forum
    $usersWithPostCount = $PostsRepository->findUsersByForum($id);
    $topLikedPosts = $PostRepository->findTopLikedPosts(5,$id);

    return $this->render('forum/statistics_per_forum.html.twig', [
        'forum' => $forum,
        'usersWithPostCount' => $usersWithPostCount,
        'topLikedPosts' => $topLikedPosts

    ]);
}

#[Route('/{id}/admin', name: 'app_forumadmin_show', methods: ['GET','POST'])]
public function showadmin(int $id, Forum $forum, EntityManagerInterface $entityManager, Request $request, ForumRepository $forumRepository, TokenGeneratorInterface $tokenGenerator): Response
{
    $postData = "";
    $post = new Post();
    $user = $this->getUser();
    $forum = $forumRepository->find($id);
    $post->setIdForum($forum);

    if ($request->isMethod('POST')) {
        
        $postData = $request->request->get('post_content');
        $post->setMessage($postData);
        $post->setNbLike(0);
        $post->setDatePost(date('Y-m-d H:i:s'));
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

    // Fetch list of users who have written posts for this forum

    // Pass the list of users to the template
    return $this->render('post/indexadmin.html.twig', [
        'posts' => $posts,
        'forum' => $forum,
        'post' => $postvide,
    
    ]);
}
    

    #[Route('/{id}/edit', name: 'app_forum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $notifier = NotifierFactory::create();
            $notification =
                    (new Notification())
                ->setTitle('Votre forum modifié')
                ->setBody('Forum portant le nom : '.$forum->getSujet().' a été modifié')
                ->setIcon(__DIR__.'../../public/img/forumicon.png');
                
            $notifier->send($notification);
    
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
