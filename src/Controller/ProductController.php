<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/list', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request,ProductRepository $productRepository,EntityManagerInterface $em): Response
    {

        //I create my form for new trick
        $product = new Product();
        // $this->denyAccessUnlessGranted('CATEGORY_CREATE', $product);
        $form = $this->createForm(ProductType::class, $product);
        // $user = $this->getUser();
        //the form request is processed
        $form->handleRequest($request);
        // if ($user === null) {
            
        //     $this->addFlash('danger', 'Veuillez vous connecter pour ajouter un trick');
        //     return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        // }
        //I check if I have a form and that it is valid
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($product);
            $em->flush();

            $productRepository->save($product, true);
            $this->addFlash(
                'success',
                'L\'article a bien été enregistré'
            );

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'editor' => $product,
            'form' => $form->createView()
        ]);
    }
}
