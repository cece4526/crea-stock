<?php

namespace App\Controller;

use App\Entity\SubCategory;
use App\Form\SubCategoryType;
use App\Repository\SubCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subCategory')]
class SubCategoryController extends AbstractController
{
    #[Route('/list', name: 'app_sub_category')]
    public function index(): Response
    {
        return $this->render('sub_category/index.html.twig', [
            'controller_name' => 'SubCategoryController',
        ]);
    }

    #[Route('/new', name: 'app_sub_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request,SubCategoryRepository $subCategoryRepository,EntityManagerInterface $em): Response
    {

        //I create my form for new trick
        $subCategory = new SubCategory();
        // $this->denyAccessUnlessGranted('CATEGORY_CREATE', $subCategory);
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        // $user = $this->getUser();
        //the form request is processed
        $form->handleRequest($request);

        // if ($user === null) {
            
        //     $this->addFlash('danger', 'Veuillez vous connecter pour ajouter un trick');
        //     return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        // }
        //I check if I have a form and that it is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $subCategory->setName(strtoupper($subCategory->getName()));

            $em->persist($subCategory);
            $em->flush();

            $subCategoryRepository->save($subCategory, true);
            $this->addFlash(
                'success',
                'La category a bien été enregistré'
            );

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sub_category/new.html.twig', [
            'category' => $subCategory,
            'form' => $form->createView()
        ]);
    }
}
