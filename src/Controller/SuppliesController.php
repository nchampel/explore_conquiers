<?php

namespace App\Controller;

use App\Repository\PlanetRepository;
use App\Service\BuildingService;
use App\Service\ConstantService;
use App\Service\TechnologyService;
use App\Service\VesselService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class SuppliesController extends AbstractController
{
    #[Route('/supplies', name: 'app_supplies')]
    public function index(PlanetRepository $planetRepository, TechnologyService $technologyService, BuildingService $buildingService, VesselService $vesselService, ConstantService $constantService): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $planet = $planetRepository->findOneBy(["user" => $user]);
        // $resources = $planetResourceRepository->findOneBy(["planet" => $planet]);
        
        return $this->render('supplies/index.html.twig', [
            'user' => $user,
            'planet' => $planet,
            // 'resources' => $resources,
            'resources' => $planet->getPlanetResource(),
            // 'buildings' => $planet->getPlanetBuildings(),
            // 'defences' => $planet->getPlanetDefences(),
            // 'vessels' => $planet->getPlanetVessels(),
            // 'researches' => $user->getResearchesPlayer(),
            'component' => 'supplies',
            'technologyService' => $technologyService,
            'buildingService' => $buildingService,
            'vesselService' => $vesselService,
            'checksConstructionInProgressByPage' => $technologyService->checksConstructionInProgressByPage("supplies", $planet),
            'domain' => $constantService->getDomain(),
        ]);
    }
}
