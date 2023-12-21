<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/list', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRepository $categoryRepository,EntityManagerInterface $em): Response
    {

        //I create my form for new trick
        $category = new Category();
        // $this->denyAccessUnlessGranted('CATEGORY_CREATE', $category);
        $form = $this->createForm(CategoryType::class, $category);
        // $user = $this->getUser();
        //the form request is processed
        $form->handleRequest($request);

        // if ($user === null) {
            
        //     $this->addFlash('danger', 'Veuillez vous connecter pour ajouter un trick');
        //     return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        // }
        //I check if I have a form and that it is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setName(strtoupper($category->getName()));

            $em->persist($category);
            $em->flush();

            $categoryRepository->save($category, true);
            $this->addFlash(
                'success',
                'La category a bien été enregistré'
            );

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }
}
