<?php

namespace App\Controller;

use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\PlanetResourceRepository;
use App\Service\ResourceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class ApiResourcesController extends AbstractController
{
    public function __construct(
        private PlanetRepository $planetRepository,
        private EntityManagerInterface $entityManager,
        private PlanetResourceRepository $planetResourceRepository,
        private ResourceService $resourceService,
        // private PlanetBuildingRepository $planetBuildingRepository
    ) {
        // parent::__construct();
    }
    #[Route('/api/resources/get', name: 'app_api_resources_get', methods: ["POST"])]
    public function getResources(Request $request): JsonResponse
    {
        // $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $planet = $data["planet"];
        $resources = $this->planetResourceRepository->findOneBy(["planet" => $planet]);

        return $this->json([
            'status_code' => 200,
            'message' => "Ressources récupérées",
            'metal' => $resources->getMetal(),
            'crystal' => $resources->getCrystal(),
            'deuterium' => $resources->getDeuterium(),
            'satellites' => $resources->getSatellite(),
            ], 200);
        // return $this->render('api_resources/index.html.twig', [
        //     'controller_name' => 'ApiResourcesController',
        // ]);
    }
    #[Route('/api/resources/production/get', name: 'app_api_resources_production_get', methods: ["POST"])]
    public function getResourcesProduction(Request $request, PlanetBuildingRepository $planetBuildingRepository, PlanetRepository $planetRepository): JsonResponse
    {
        // $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $planetId = $data["planet"];
        $planet = $planetRepository->findOneBy(["id" => $planetId]);
        $resources = $this->planetResourceRepository->findOneBy(["planet" => $planet]);


        $resources = ["metal", "crystal", "deuterium"];
        $resourcesProductions = [];
        $productionBuildings = $planetBuildingRepository->findBuildingProductionByPlanet($planet);
        // echo count($buildingLevels);
        foreach($resources as $resource){

            $resourcesProductions[$resource] = $this->resourceService->incrementResource($resource, $planet, $productionBuildings);
        }

        return $this->json([
            'status_code' => 200,
            'message' => "Productions récupérées",
            "resourcesProductions" => $resourcesProductions,
            // 'metal' => $resources->getMetal(),
            // 'crystal' => $resources->getCrystal(),
            // 'deuterium' => $resources->getDeuterium(),
            // 'satellites' => $resources->getSatellite(),
            ], 200);
        // return $this->render('api_resources/index.html.twig', [
        //     'controller_name' => 'ApiResourcesController',
        // ]);
    }
}
