<?php

namespace App\Controller;

use App\Entity\Research;
use App\Form\ResearchType;
use App\Repository\ResearchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/research')]
class ResearchController extends AbstractController
{
    #[Route('/', name: 'app_research_index', methods: ['GET'])]
    public function index(ResearchRepository $researchRepository): Response
    {
        return $this->render('research/index.html.twig', [
            'research' => $researchRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_research_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $research = new Research();
        $form = $this->createForm(ResearchType::class, $research);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($research);
            $entityManager->flush();

            return $this->redirectToRoute('app_research_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('research/new.html.twig', [
            'research' => $research,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_research_show', methods: ['GET'])]
    public function show(Research $research): Response
    {
        return $this->render('research/show.html.twig', [
            'research' => $research,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_research_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Research $research, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResearchType::class, $research);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_research_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('research/edit.html.twig', [
            'research' => $research,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_research_delete', methods: ['POST'])]
    public function delete(Request $request, Research $research, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$research->getId(), $request->request->get('_token'))) {
            $entityManager->remove($research);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_research_index', [], Response::HTTP_SEE_OTHER);
    }
}
