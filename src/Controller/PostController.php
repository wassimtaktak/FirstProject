<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Utilisateur;
use App\Form\PostType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UserLikes;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/new/{id}', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,int $id,ForumRepository $forumRepository): Response
    {
        $post = new Post();
        $forum = $forumRepository->find($id);
        $post->setIdForum($forum);
        
        
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
    #[Route('/{id}/like', name: 'app_like_add', methods : ['GET'])]
    public function nblike(Post $post,EntityManagerInterface $entityManager):Response
    {
        // Get the current user from the session or authentication context
        $user = new Utilisateur();
        
        $user->setId(5);

    // Assuming you have a user entity with an ID property
    $userId = $user->getId();

    // Check if the user has already liked the post
    $userLiked = $entityManager->getRepository(UserLikes::class)->findOneBy([
        'user' => $user,
        'post' => $post,
    ]);

    // If the user has not already liked the post, add a like
    if (!$userLiked) {
        // Update the like count of the post
        $post->setNbLike($post->getNbLike() + 1);

        // Create a new UserLikes entity and set its properties
        $userLike = new UserLikes();
        $userLike->setUser($user);
        $userLike->setPost($post);

        // Persist the UserLikes entity and flush changes to the database
        $entityManager->persist($userLike);
        $entityManager->flush();
    }

    // Redirect to the forum page or any other appropriate page
    return $this->redirect("/forum/".$post->getIdForum()->getId(), Response::HTTP_SEE_OTHER);
        
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        
        if ($request->isMethod('POST')) {
            $postData = $request->request->get('post_content');
            $post->setMessage($postData);
            $entityManager->persist($post);
            $entityManager->flush();

            //return $this->redirectToRoute('app_forum_show', [], Response::HTTP_SEE_OTHER);
            return $this->redirect("/forum/".$post->getIdForum()->getId(), Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit/admin', name: 'app_postadmin_edit', methods: ['GET', 'POST'])]
    public function editadmin(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        
        if ($request->isMethod('POST')) {
            $postData = $request->request->get('post_content');
            $post->setMessage($postData);
            $entityManager->persist($post);
            $entityManager->flush();

            //return $this->redirectToRoute('app_forum_show', [], Response::HTTP_SEE_OTHER);
            return $this->redirect("/forum/".$post->getIdForum()->getId(), Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/editadmin.html.twig', [
            'post' => $post,
        ]);
    }
    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        
            $entityManager->remove($post);
            $entityManager->flush();
        

        return $this->redirect("/forum/".$post->getIdForum()->getId(), Response::HTTP_SEE_OTHER);
    }
}
