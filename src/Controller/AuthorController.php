<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class AuthorController extends AbstractController
{   
    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
        'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
        ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
        'taha.hussein@gmail.com', 'nb_books' => 300),
        );
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/author/{name}', name: 'author_show')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }
    #[Route('/list', name: 'list_show')]
    public function lisauthor(): Response
    {   
        
        return $this->render('author/list.html.twig', [
            'authors' => $this->authors,
        ]);
    }
    #[Route('/details/{id}', name: 'detail_id')]
    public function detailsAuthor($id): Response
    {   
        $author = null;
        foreach ($this->authors as $a) {
            if ($a['id'] == $id) {
                $author = $a;
                break; // Sortez de la boucle une fois que vous avez trouvé l'auteur
            }
        }    
        return $this->render('author/showAuthor.html.twig', ['id' => $id,'author' => $a,]);
    }
    #[Route('/listauthors', name: 'list_authors')]
    public function findallauthors(AuthorRepository $authorrepository): Response
    {   
        $auteurs = $authorrepository->findAll();
        return $this->render('author/allauthors.html.twig', ['authors' => $auteurs,]);
    }
    #[Route('/auteur/add', name: 'author_ajout')]
    public function addauthors(ManagerRegistry $doctrine,Request $request)
    {   
        $em=$doctrine->getManager();
        $auteur = new Author();
        /*$auteur->setUsername("wassim");
        $auteur->setEmail("wassim@gmail.com");*/
        $form = $this->createForm(AuthorType::class, $auteur);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em->persist($auteur);
            $em->flush($auteur);
            return $this->redirectToRoute('list_authors');
        }
        
            return $this->renderForm('author/add.html.twig',['form'=>$form]);  
    }
    #[Route('/delete/{id}', name: 'app_author_delete')]
    public function delete(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        // Recherchez l'auteur par son ID
        $author = $entityManager->getRepository(Author::class)->find($id);

        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('list_authors');
    }
    #[Route('edit/{id}', name: 'app_author_edit')]
    public function edit(Request $request,int $id, ManagerRegistry $doctrine): Response
    {   
        $entityManager=$doctrine->getManager();
        $author = $entityManager->getRepository(Author::class)->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('list_authors');
        }
        $form->add('submit', SubmitType::class, [
            'label' => 'Editer', // Personnalisez le label du bouton
        ]);
        return $this->renderForm('author/edit.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }
    #[Route('/showordered', name: 'ordered_username')]
    public function findauthorsorderbyusername(AuthorRepository $authorrepository): Response
    {   
        $auteurs = $authorrepository->triusername();
        return $this->render('author/allauthors.html.twig', ['authors' => $auteurs,]);
    }
    #[Route('/showdql', name: 'dqlorder')]
    public function findauthorsdql(AuthorRepository $authorrepository): Response
    {   
        $auteurs = $authorrepository->tridql();
        return $this->render('author/allauthors.html.twig', ['authors' => $auteurs,]);
    }
}
