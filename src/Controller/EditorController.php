<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/editor')]
class EditorController extends AbstractController
{
    #[Route('/list', name: 'app_editor')]
    public function index(): Response
    {
        return $this->render('editor/index.html.twig', [
            'controller_name' => 'EditorController',
        ]);
    }

    #[Route('/new', name: 'app_editor_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EditorRepository $editorRepository,EntityManagerInterface $em): Response
    {

        //I create my form for new trick
        $editor = new Editor();
        // $this->denyAccessUnlessGranted('CATEGORY_CREATE', $editor);
        $form = $this->createForm(EditorType::class, $editor);
        // $user = $this->getUser();
        //the form request is processed
        $form->handleRequest($request);

        // if ($user === null) {
            
        //     $this->addFlash('danger', 'Veuillez vous connecter pour ajouter un trick');
        //     return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        // }
        //I check if I have a form and that it is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $editor->setName(strtoupper($editor->getName()));

            $em->persist($editor);
            $em->flush();

            $editorRepository->save($editor, true);
            $this->addFlash(
                'success',
                'La category a bien été enregistré'
            );

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('editor/new.html.twig', [
            'editor' => $editor,
            'form' => $form->createView()
        ]);
    }
}
