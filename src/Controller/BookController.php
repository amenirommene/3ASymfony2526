<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
 #[Route('/book')]
final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

      #[Route('/getAll', name: 'app_book_getAll')]
    public function getAllAuthor(ManagerRegistry $doctrine): Response
    {
       $repo=$doctrine->getRepository(Book::class);
        $books=$repo->findAll();
        return $this->render('book/list.html.twig', [
            'list' => $books,
        ]);
    }
     #[Route('/getEnabledBook', name: 'app_book_enabled')]
    public function getEnabledBook(ManagerRegistry $doctrine): Response
    {
       $repo=$doctrine->getRepository(Book::class);
        $books=$repo->findBy(['enabled'=>true]);
        return $this->render('book/list.html.twig', [
            'list' => $books,
        ]);
    }

     #[Route('/new', name: 'app_book_newForm')]
    public function addBook(Request $request,ManagerRegistry $doctrine): Response
    {
        
        $book = new Book();
        $form=$this->createForm(BookType::class,$book);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        //remplissage de l'objet $author
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
       $em->persist($book);
       $em->flush(); 
      // return new Response("Ajout effectuée avec succès");
      return $this->redirectToRoute("app_book_getAll");
        }
        return 
        $this->render("book/add.html.twig",["f"=>$form->createView()]);
    }

     #[Route('/update/{id}', name: 'app_book_updateForm')]
    public function updateAuthor(BookRepository $repo, Request $request,ManagerRegistry $doctrine): Response
    {
        
        $book = $repo->find($request->get('id'));
        $form=$this->createForm(BookType::class,$book);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        //remplissage de l'objet $book
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->flush(); 
      // return new Response("Modification effectuée avec succès");
      return $this->redirectToRoute("app_book_getAll");
        }
        return 
        $this->render("book/add.html.twig",["f"=>$form->createView()]);
    }
    //1ère façon pour récupérer un id envoyé dans l'url
     #[Route('/delete1/{id}', name: 'app_book_delete1')]
    public function deleteBook($id, BookRepository $repo, ManagerRegistry $doctrine): Response
    {
        $book= $repo->find($id); 
        if ($book){
        $em=$doctrine->getManager();
        $em->remove($book);
        $em->flush();
        
        }
        return $this->redirectToRoute("app_book_getAll");
    }
    //2ème façon pour récupérer un id envoyé dans l'url
     #[Route('/delete2/{id}', name: 'app_book_delete2')]
    public function deleteBook2(Request $request, BookRepository $repo, ManagerRegistry $doctrine): Response
    {
        $id=$request->get('id');
        $book= $repo->find($id); 
        if ($book){
        $em=$doctrine->getManager();
        $em->remove($book);
        $em->flush();
        }
           return $this->redirectToRoute("app_book_getAll");
    }
}
