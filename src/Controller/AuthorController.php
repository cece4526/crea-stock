<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/list', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/new', name: 'app_author_new', methods: ['GET', 'POST'])]
    public function new(Request $request,AuthorRepository $authorRepository,EntityManagerInterface $em): Response
    {

        $author = new Author();
        // $this->denyAccessUnlessGranted('CATEGORY_CREATE', $author);
        $form = $this->createForm(AuthorType::class, $author);
        // $user = $this->getUser();
        $form->handleRequest($request);

        // if ($user === null) {
            
        //     $this->addFlash('danger', 'Veuillez vous connecter pour ajouter un trick');
        //     return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        // }
        if ($form->isSubmitted() && $form->isValid()) {
            $author->setName(strtoupper($author->getName()));

            $em->persist($author);
            $em->flush();

            $authorRepository->save($author, true);
            $this->addFlash(
                'success',
                'La category a bien été enregistré'
            );

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('author/new.html.twig', [
            'editor' => $author,
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{name}', name: 'app_author_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AuthorRepository $authorRepository, EntityManagerInterface $em): Response
    {
        $author = new Author();
        $authorSlug = $request->attributes->get('name');
        $author = $authorRepository->findOneBySomeField($authorSlug);

        // $this->denyAccessUnlessGranted('TRICK_EDIT', $author);
        //I create my form for edit trick
        $form = $this->createForm(AuthorType::class, $author);
        // $user = $this->getUser();
        //the form request is processed
        $form->handleRequest($request);

        // if ($user === null) {
            
        //     $this->addFlash('danger', 'Veuillez vous connecter pour modifier un trick');
        //     return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        // }
        //I check if I have a form and that it is valid
        if ($form->isSubmitted() && $form->isValid()) {


            $author->setName(strtoupper($author->getName()));


            // $em->persist($author);
            // $em->flush();

            $authorRepository->save($author, true);
            $this->addFlash(
                'success',
                'Le trick a bien été enregistré'
            );

            // return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('author/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView()
        ]);
    }

}
