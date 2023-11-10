<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\searchType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\BookRepository;
#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/all', name: 'app_book')]
    public function findallbooks(BookRepository $bookrepository): Response
    {   //>findBy(array(),array('nom'=>'ASC'));
        //findall()
        $books = $bookrepository->findBy(array(),array('title'=>'ASC'));
        return $this->render('book/show.html.twig', ['books' => $books,]);
    }
    #[Route('/new', name: 'app_book_new')]
    public function new(ManagerRegistry $doctrine,Request $request): Response
    {   
        $entityManager=$doctrine->getManager();
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book');
        }

        return $this->renderForm('book/new.html.twig', ['book' => $book,'form' => $form,]);
    }
    #[Route('/delete/{id}', name: 'app_book_delete')]
    public function delete(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        // Recherchez l'auteur par son ID
        $book = $entityManager->getRepository(Book::class)->find($id);

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('app_book');
    }
    #[Route('edit/{id}', name: 'app_book_edit')]
    public function edit(Request $request,int $id, ManagerRegistry $doctrine): Response
    {   
        $entityManager=$doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book');
        }
        $form->add('submit', SubmitType::class, [
            'label' => 'Editer', // Personnalisez le label du bouton
        ]);
        return $this->renderForm('author/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }
    #[Route('/search', name: 'booksearch')]
    public function search(BookRepository $bookRepository ,Request $request): Response
    {
        $book=new book();
        $form=$this->createForm(SearchType::class,$book);
        $form->handleRequest($request);
        $title=$form->get('title')->getData();
        if($form->isSubmitted())
        {
            $books=$bookRepository->searchBookByRef($title);
      
    
        return $this->render('book/show.html.twig', [
            'book' => $books,
        ]);
    }
        else
        {
            return $this->render('book/show.html.twig', [
                'form' => $form,
            ]);
        }
    }

}

