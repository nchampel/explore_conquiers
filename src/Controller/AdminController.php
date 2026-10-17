<?php

namespace App\Controller;

use App\Service\PlanetService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entity, private PlanetService $planetService){
    }
    #[Route('/show', name: 'app_admin_show')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/generate/planets', name: 'app_admin_generate_planets')]
    public function generatePlanets(): Response
    {
        $this->planetService->generatePlanets(1);
        
        return $this->render('admin/generatePlanets.html.twig', [
            // 'controller_name' => 'AdminController',
        ]);
    }
}
