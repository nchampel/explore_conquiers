<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiPlanetController extends AbstractController
{
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
        // $playerPosition = ["latitude" => 43.4237596, "longitude" => 5.2876443];
        // $data = json_decode($request->getContent(), true);
        // $latitudeData = $data['latitude'] ?? null;
        // $longitudeData = $data['longitude'] ?? null;
        // $playerPosition = ["latitude" => $latitudeData, "longitude" => $longitudeData];
        // // on récupère les ressources récoltables et on vérifie si elles sont pas périmées
        // $recoltableResources = [];
        // $resources = $resourceRepository->findResourcesAroundPlayer($playerPosition);
        // $countOutdatedResources = 0;
        // $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

        // foreach($resources as $resource){
        //     $isOutdated = $resourceService->determinateResourceIsCollectable($resource, $now);
        //     if($isOutdated){
        //         $countOutdatedResources++;
        //         $resource->setIsCollectable(false);
        //         $this->entityManager->persist($resource);
        //     } else {
        //         $recoltableResources[] = $resource;
        //     }
        // }
        // if($countOutdatedResources > 0){
        //     $this->entityManager->flush();
        // }

        // return $this->json([
        //         'message' => "Ressources récupérées",
        //             'resources' => $recoltableResources,
        //         ], 200, [], [
        //             'groups' => [
        //                 'resource:read',
        //             ],
        //     ]);
        
    

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
