<?php

namespace App\Controller;

use App\Repository\PlanetRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiPlanetController extends AbstractController
{
    public function __construct(private PlanetRepository $planetRepository, private LoggerInterface $logger){}

    // #[Route('/api/planet', name: 'app_api_planet')]
    // public function index(): Response
    // {
    //     return $this->render('api_planet/index.html.twig', [
    //         'controller_name' => 'ApiPlanetController',
    //     ]);
    // }
    #[Route('/gps/planet/get', name: 'app_api_gps_planets_get', methods: ["POST"])]
    public function index(Request $request): Response
    {
        $playerPosition = ["latitude" => 43.4237596, "longitude" => 5.2876443];
        $planets = $this->planetRepository->findPlanetsAroundPlayer($playerPosition);
        

        foreach($planets as $planet){
            $this->logger->notice($planet->getId());
        }

        return $this->json([
                'message' => "Planètes récupéréees",
                    'planets' => $planets,
                ], 200, [], [
                    'groups' => [
                        'planet:read',
                    ],
            ]);
        
    

        // return $this->json([
        //     [
        //         "id" => 1,
        //         "latitude" => 43.5297,
        //         "longitude" => 5.4474,
        //         "activationRadius" => 50,
        //         "imageUrl" => "https://...",
        //         "type" => "wood"
        //         ],
        //     ]);
        
    return $this->json([
        [
        "id" => 1,
        "latitude" => 43.52975,
        "longitude" => 5.44740,
        "activationRadius" => 15,
        "type" => "wood"
    ],
    [
        "id" => 2,
        "latitude" => 43.52985,
        "longitude" => 5.44745,
        "activationRadius" => 15,
        "type" => "water"
    ],
        [
        "id" => 15,
        "latitude" => 37.4219983,
        "longitude" => -122.08410,
        "activationRadius" => 15,
        "type" => "wood"
    ],
    [
        "id" => 4,
        "latitude" => 37.4219987,
        "longitude" => -122.08390,
        "activationRadius" => 15,
        "type" => "water"
    ],
        [
        "id" => 5,
        "latitude" => 43.4237600,
        "longitude" => 5.2875000,
        "activationRadius" => 15,
        "type" => "wood"
    ],
    [
        "id" => 6,
        "latitude" => 43.4237682,
        "longitude" => 5.2873261,
        "activationRadius" => 15,
        "type" => "water"
    ],
        [
        "id" => 7,
        "latitude" => 37.4220295,
        "longitude" => -122.08610,
        "activationRadius" => 15,
        "type" => "treasure"
    ],
    [
        "id" => 8,
        "latitude" => 37.4219815,
        "longitude" => -122.08370,
        "activationRadius" => 15,
        "type" => "metal"
    ],
    // [
    //     "id" => 9,
    //     "latitude" => 43.4237402,
    //     "longitude" => 5.2875569,
    //     "activationRadius" => 15,
    //     "type" => "water"
    // ],
    [
        "id" => 10,
        "latitude" => 43.4237402,
        "longitude" => 5.2875669,
        "activationRadius" => 15,
        "type" => "water"
    ],
    ]);
        
    }
}
