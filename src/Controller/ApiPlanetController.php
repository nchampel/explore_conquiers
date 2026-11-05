<?php

namespace App\Controller;

use App\Entity\LogPosition;
use App\Repository\GpsPointRepository;
use App\Repository\PlanetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiPlanetController extends AbstractController
{
    public function __construct(private PlanetRepository $planetRepository, private LoggerInterface $logger, private EntityManagerInterface $entity,
    private GpsPointRepository $gpsPointRepository){}

    // #[Route('/api/planet', name: 'app_api_planet')]
    // public function index(): Response
    // {
    //     return $this->render('api_planet/index.html.twig', [
    //         'controller_name' => 'ApiPlanetController',
    //     ]);
    // }
    #[Route('/gps/planet/get', name: 'api_gps_planets_get', methods: ["POST"])]
    public function index(Request $request): Response
    {
        // return $this->json([
        //     "planets" => [["id" => 135, "gpsPoint" => ["id" => 1, "latitude" => 37.4219983,
        //     "longitude" => -122.084,
        //     "activationRadius" => 15,
        //     "type" => "wood"]]
        //     ]
            
        // ]);
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

    private function getGainForReachingPlanet(int $visited){
        switch($visited){
            case 0:
            case 1:
            case 2:
                return 2;
                
            case 4:
            case 5:
                return 4;
                
            case 6:
            case 7:
                return 8;
                
            case 8:
            case 9:
                return 12;
                
            case 10:
            case 11:
                return 16;
            case 12:
            case 13:
                return 22;
            case 14:
            case 15:
                return 30;
                
            
        }
    }
    private function getGainForTravel(int $distance){
        return round($distance / 50);
    }
    private function getGainForValidatingQuests(){}

    #[Route('/gps/planet/quest/validate', name: 'api_gps_planet_quest_validate', methods: ["POST"])]
    public function validateQuest(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $planetId = $data['planetId'] ?? null;
        $distance = $data['distance'] ?? null;
        $planet = $this->planetRepository->findOneBy(["id" => $planetId]);
        if(!$planet->getGpsPoint()->isVisitedForDailyQuest()){
            $dailyVisitedPlanets = count($this->gpsPointRepository->findBy(["isVisitedForDailyQuest" => true]));
            $gainForReachingPlanet = $this->getGainForReachingPlanet($dailyVisitedPlanets);
            $gainForTravel = $this->getGainForTravel($distance);
            $gainForValidatingQuests = 0;
            $gain = $gainForReachingPlanet + $gainForTravel + $gainForValidatingQuests;
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $moral = $user->getMoral();
            $user->setMoral($moral + $gain);
            $planet->getGpsPoint()->setIsVisitedForDailyQuest(true);
            $this->entity->flush();
            return $this->json([
                "message" => "Planète visitée id : " . $planetId,
                "moral" => $gain
            ], 200);
            } else {
            return $this->json([
                "message" => "Planète déjà visitée id : " . $planetId,
                "moral" => 0,
            ], 200);

        }
        // $longitudeData = $data['longitude'] ?? null;
        
    }
    #[Route('/gps/quest/log-position/save', name: 'api_gps_quest_log_position_save', methods: ["POST"])]
    public function saveLogPosition(Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $data = json_decode($request->getContent(), true);
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;
        $accuracy = $data['accuracy'] ?? null;
        $stepDistance = $data['stepDistance'] ?? null;
        $startPlanetId = $data['startPlanetId'] ?? null;
        $destinationPlanetId = $data['destinationPlanetId'] ?? null;

        $logPosition = new LogPosition();
        $logPosition->setUser($this->getUser());
        $logPosition->setAccuracy($accuracy);
        $logPosition->setLatitude($latitude);
        $logPosition->setLongitude($longitude);
        $logPosition->setSpeed(round(($stepDistance / 10) * (3600 / 1000), 1));
        $logPosition->setStepDistance($stepDistance);
        $logPosition->setStartPlanet($startPlanetId);
        $logPosition->setDestinationGpsPoint($destinationPlanetId);
        $logPosition->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));
        $this->entity->persist($logPosition);
        $this->entity->flush();
        // $planet = $this->planetRepository->findOneBy(["id" => $planetId]);
        
        
        return $this->json([
            "message" => "Log de position enregistré",
        ], 200);
           
        
    }
}
