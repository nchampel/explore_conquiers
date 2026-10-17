<?php

namespace App\Controller;

use App\Entity\Defence;
use App\Form\DefenceType;
use App\Repository\DefenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/defence')]
class DefenceController extends AbstractController
{
    #[Route('/', name: 'app_defence_index', methods: ['GET'])]
    public function index(DefenceRepository $defenceRepository): Response
    {
        return $this->render('defence/index.html.twig', [
            'defences' => $defenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_defence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $defence = new Defence();
        $form = $this->createForm(DefenceType::class, $defence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($defence);
            $entityManager->flush();

            return $this->redirectToRoute('app_defence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('defence/new.html.twig', [
            'defence' => $defence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_defence_show', methods: ['GET'])]
    public function show(Defence $defence): Response
    {
        return $this->render('defence/show.html.twig', [
            'defence' => $defence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_defence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Defence $defence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DefenceType::class, $defence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_defence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('defence/edit.html.twig', [
            'defence' => $defence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_defence_delete', methods: ['POST'])]
    public function delete(Request $request, Defence $defence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$defence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($defence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_defence_index', [], Response::HTTP_SEE_OTHER);
    }
}
