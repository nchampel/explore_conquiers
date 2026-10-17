<?php

namespace App\Controller;

use App\Entity\Vessel;
use App\Form\VesselType;
use App\Repository\VesselRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/vessel')]
class VesselController extends AbstractController
{
    #[Route('/', name: 'app_vessel_index', methods: ['GET'])]
    public function index(VesselRepository $vesselRepository): Response
    {
        return $this->render('vessel/index.html.twig', [
            'vessels' => $vesselRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vessel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vessel = new Vessel();
        $form = $this->createForm(VesselType::class, $vessel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vessel);
            $entityManager->flush();

            return $this->redirectToRoute('app_vessel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vessel/new.html.twig', [
            'vessel' => $vessel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vessel_show', methods: ['GET'])]
    public function show(Vessel $vessel): Response
    {
        return $this->render('vessel/show.html.twig', [
            'vessel' => $vessel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vessel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vessel $vessel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VesselType::class, $vessel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vessel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vessel/edit.html.twig', [
            'vessel' => $vessel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vessel_delete', methods: ['POST'])]
    public function delete(Request $request, Vessel $vessel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vessel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vessel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vessel_index', [], Response::HTTP_SEE_OTHER);
    }
}
