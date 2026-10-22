<?php

namespace App\Service;

use App\Entity\Building;
use App\Entity\Planet;
use App\Repository\PlanetBuildingRepository;
use Psr\Log\LoggerInterface;

class BuildingService
{
    public function __construct(private PlanetBuildingRepository $planetBuildingRepository, private LoggerInterface $logger) {
    }
    public function getBuildingsCountByPlanet(Planet $planet): int{
        $buildings = $this->planetBuildingRepository->findBy(["planet" => $planet]);
        $count = 0;
        foreach($buildings as $building) {
            $count += $building->getLevel();
        }
        return $count;
    }
    public function getTechnoIDCorrespondence(int $technology): int{
        switch($technology){
            case 1:
                // mine de métal
                return 1;
                break;
            case 2:
                // mine de cristal
                return 2;
                break;
            case 3:
                // synthétiseur de deutérium
                return 3;
                break;
            case 4:
                // centrale électrique solaire
                return 4;
                break;
            case 12:
                // centrale électrique de fusion
                return 5;
                break;
            case 22:
                // hangar de métal
                return 5;
                break;
            case 23:
                // hangar de cristal
                return 5;
                break;
            case 24:
                // réservoir de deutérium
                return 5;
                break;
        }
        return 0;
    }
    public function getFactorNeededResource(int $buildingId): float{
        switch($buildingId){
            case 1:
                // mine de métal
                return 1.5;
                break;
            case 2:
                // mine de cristal
                return 1.6;
                break;
            case 3:
                // synthétiseur de deutérium
                return 1.5;
                break;
            case 4:
                // centrale électrique solaire
                return 1.5;
                break;
            case 5:
                // centrale électrique de fusion
                return 1.8;
                break;
            case 6:
                // hangar de métal
                return 2;
                break;
            case 7:
                // hangar de cristal
                return 2;
                break;
            case 8:
                // réservoir de deutérium
                return 2;
                break;
        }
        return 0;
    }
    public function getResourcesToBuild(int $level, Building $building): array{
        $array = [];
        $factor = $this->getFactorNeededResource($building->getId());
        $array["metal"] = floor($building->getMetal() * $factor ** ($level + 1 - 1));
        $array["crystal"] = floor($building->getCrystal() * $factor ** ($level + 1 - 1));
        $array["deuterium"] = floor($building->getDeuterium() * $factor ** ($level + 1 - 1));
        return $array;
    }
    // public function calculateResourcesToBuild(int $level, Building $building): array{
    //     $array = [];
        
    //     return $array;
    // }
    // private function get
    public function getTechnologiesSupplies(int $technologyId, Planet $planet): array{
        $array = [];
        $buildingId = $this->getTechnoIDCorrespondence($technologyId);
        // $buildingPlanet = $planet->getBuildingByName("Mine de cristal");
        $this->logger->info($buildingId);
        $buildingPlanet = $planet->getBuildingById($buildingId);
        $building = $buildingPlanet->getBuilding();

        // $neededMetal = $building->getMetal();
        // $neededResources = ["metal" => $neededMetal, "crystal"  => 0, "deuterium" => 0];
        switch($buildingId){
            case 1:
                // $this->logger->info($buildingId);
                $array["buildingNameEn"] = "metalMine";
                $array["buttonTitle"] = "Aucune condition requise";
                
                break;
            case 2:
                $array["buildingNameEn"] = "crystalMine";
                $array["buttonTitle"] = "Aucune condition requise";
                break;
            case 3:
                $array["buildingNameEn"] = "deuteriumSynthetizer";
                $array["buttonTitle"] = "Aucune condition requise";
                break;
            case 4:
                $array["buildingNameEn"] = "solarPlant";
                $array["buttonTitle"] = "Aucune condition requise";
                break;
            case 5:
                $array["buildingNameEn"] = "fusionPlant";
                $array["buttonTitle"] = "Afficher arbre tech";
                break;
            case 6:
                $array["buildingNameEn"] = "metalStorage";
                $array["buttonTitle"] = "Aucune condition requise";
                break;
            case 7:
                $array["buildingNameEn"] = "crystalStorage";
                $array["buttonTitle"] = "Aucune condition requise";
                break;
            case 8:
                $array["buildingNameEn"] = "deuteriumStorage";
                $array["buttonTitle"] = "Aucune condition requise";
                break;
        }
        $array["buildingName"] = $building->getName();
        $array["buildingLevel"] = $buildingPlanet->getLevel();
        $array["buildingNeededResources"] = $this->getResourcesToBuild($buildingPlanet->getLevel(), $building);
        return $array;
    }
}