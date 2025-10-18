<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/author')]
final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showauthor/test', name: 'app_author_index2')]
    public function index2(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showauthor/{name}', name: 'app_author_show')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'authorName' => $name,
        ]);
    }
    #[Route('/getA/{id}', name: 'app_author_details')]
    public function getAuthor(AuthorRepository $repo, $id): Response
    {
        $author = $repo->find($id);
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }
     #[Route('/new', name: 'app_author_new')]
    public function newAuthor(ManagerRegistry $doctrine): Response
    {
        //preparation de l'objet à stocker
        $author = new Author();
        $author->setUsername("arwa");
        $author->setEmail("arwa@esprit.tn");
        $author->setNbBooks(60);
        //
       $em=$doctrine->getManager();
       $em->persist($author);
       $em->flush();
       // return new Response("Ajout affectué");
        return $this->redirectToRoute("app_author_getAll");
    }
     #[Route('/getAll', name: 'app_author_getAll')]
    public function getAllAuthor(ManagerRegistry $doctrine): Response
    {
       $repo=$doctrine->getRepository(Author::class);
        $authors=$repo->findAll();
        return $this->render('author/list.html.twig', [
            'listAuthors' => $authors,
        ]);
    }

     #[Route('/newForm', name: 'app_author_newForm')]
    public function addAuthor(Request $request,ManagerRegistry $doctrine): Response
    {
        
        $author = new Author();
        $form=$this->createForm(AuthorType::class,$author);
        $form->add("Submit", SubmitType::class);
        $form->handleRequest($request);
        //remplissage de l'objet $author
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
       $em->persist($author);
       $em->flush(); 
      // return new Response("Ajout effectuée avec succès");
       return $this->redirectToRoute("app_author_getAll");
        }
        return 
        $this->render("author/add.html.twig",["f"=>$form->createView()]);
      //  return new Response("Ajout affectué");
    }


     #[Route('/updateForm/{id}', name: 'app_author_updateForm')]
    public function updateAuthor(AuthorRepository $repo, Request $request,ManagerRegistry $doctrine): Response
    {
        
        $author = $repo->find($request->get('id'));
        $form=$this->createForm(AuthorType::class,$author);
        $form->add("Submit", SubmitType::class);
        $form->handleRequest($request);
        //remplissage de l'objet $author
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
     //  $em->persist($author);
       $em->flush(); 
        return $this->redirectToRoute("app_author_getAll");
       //return new Response("Ajout effectuée avec succès");
        }
        return 
        $this->render("author/add.html.twig",["f"=>$form->createView()]);
    }

     #[Route('/delete/{id}', name: 'app_author_delete')]
    public function deleteAuthor($id, AuthorRepository $repo, ManagerRegistry $doctrine): Response
    {
        $author= $repo->find($id); 
        if ($author){
        $em=$doctrine->getManager();
        $em->remove($author);
        $em->flush();
        
        }
        return $this->redirectToRoute("app_author_getAll");
    }
    
}
