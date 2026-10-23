<?php

namespace App\Service;

use App\Entity\Planet;
use App\Entity\Vessel;

use App\Repository\PlanetVesselRepository;
// use Psr\Log\LoggerInterface;

class VesselService
{
    public function __construct(private PlanetVesselRepository $planetVesselRepository
    // , private LoggerInterface $logger
    ) {
    }
    public function getVesselsNumberByNameByPlanet(Planet $planet, Vessel $vessel): int{
        $vessels = $this->planetVesselRepository->findOneBy(["planet" => $planet, "vessel" => $vessel]);
        // $count = 0;
        // foreach($buildings as $building) {
        //     $count += $building->getLevel();
        // }
        return $vessels->getNumber();
    }
    public function getTechnoIDCorrespondence(int $technology): int{
        switch($technology){
            case 1:
                // chasseur léger
                return 1;
                break;
            case 2:
                // chasseur lourd
                return 2;
                break;
            case 3:
                // croiseur
                return 3;
                break;
            case 4:
                // vaisseau de bataille
                return 4;
                break;
            case 12:
                // traqueur
                return 5;
                break;
            case 22:
                // bombardier
                return 6;
                break;
            case 23:
                // destructeur
                return 7;
                break;
            case 24:
                // étoile de la mort
                return 8;
                break;
            case 22:
                // petit transporteur
                return 9;
                break;
            case 23:
                // grand transporteur
                return 10;
                break;
            case 24:
                // vaisseau de colonisation
                return 11;
                break;
            case 22:
                // recycleur
                return 12;
                break;
            case 23:
                // sonde d'espionnage
                return 13;
                break;
            case 212:
                // satellite solaire
                return 14;
                break;
        }
        return 0;
    }
    public function getNeededResource(int $vesselId): array{
        switch($vesselId){
            case 14:
                // satellite
                return ["metal" => 0, "crystal" => 2000, "deuterium" => 500];
                break;
            // case 2:
            //     // mine de cristal
            //     return 1.6;
            //     break;
            // case 3:
            //     // synthétiseur de deutérium
            //     return 1.5;
            //     break;
            // case 4:
            //     // centrale électrique solaire
            //     return 1.5;
            //     break;
            // case 5:
            //     // centrale électrique de fusion
            //     return 1.8;
            //     break;
            // case 6:
            //     // hangar de métal
            //     return 2;
            //     break;
            // case 7:
            //     // hangar de cristal
            //     return 2;
            //     break;
            // case 8:
            //     // réservoir de deutérium
            //     return 2;
            //     break;
        }
        return [];
    }
    public function getResourcesToBuild(Vessel $vessel): array{
        // $array = [];
        return $this->getNeededResource($vessel->getId());
        // $array["metal"] = floor($vessel->getMetal() * $factor ** ($level + 1 - 1));
        // $array["crystal"] = floor($vessel->getCrystal() * $factor ** ($level + 1 - 1));
        // $array["deuterium"] = floor($vessel->getDeuterium() * $factor ** ($level + 1 - 1));
        // return $array;
    }
    
    // private function get
    public function getTechnologiesSupplies(int $technologyId, Planet $planet): array{
        $array = [];
        $vesselId = $this->getTechnoIDCorrespondence($technologyId);
        // $vesselPlanet = $planet->getvesselByName("Mine de cristal");
        // $this->logger->info($vesselId);
        $vesselPlanet = $planet->getVesselById($vesselId);
        $vessel = $vesselPlanet->getVessel();

        // $neededMetal = $building->getMetal();
        // $neededResources = ["metal" => $neededMetal, "crystal"  => 0, "deuterium" => 0];
        switch($vesselId){
            case 14:
                // $this->logger->info($vesselId);
                $array["buildingNameEn"] = "solarSatellite";
                $array["vesselNameEn"] = "solarSatellite";
                $array["buttonTitle"] = "Afficher arbre tech";
                
                break;
            // case 2:
            //     $array["buildingNameEn"] = "crystalMine";
            //     $array["buttonTitle"] = "Aucune condition requise";
            //     break;
            // case 3:
            //     $array["buildingNameEn"] = "deuteriumSynthetizer";
            //     $array["buttonTitle"] = "Aucune condition requise";
            //     break;
            // case 4:
            //     $array["buildingNameEn"] = "solarPlant";
            //     $array["buttonTitle"] = "Aucune condition requise";
            //     break;
            // case 5:
            //     $array["buildingNameEn"] = "fusionPlant";
            //     $array["buttonTitle"] = "Afficher arbre tech";
            //     break;
            // case 6:
            //     $array["buildingNameEn"] = "metalStorage";
            //     $array["buttonTitle"] = "Aucune condition requise";
            //     break;
            // case 7:
            //     $array["buildingNameEn"] = "crystalStorage";
            //     $array["buttonTitle"] = "Aucune condition requise";
            //     break;
            // case 8:
            //     $array["buildingNameEn"] = "deuteriumStorage";
            //     $array["buttonTitle"] = "Aucune condition requise";
            //     break;
        }
        $number = $this->getVesselsNumberByNameByPlanet($planet, $vessel);
        $resources = $this->getResourcesToBuild($vessel);
        $array["buildingName"] = $vessel->getName();
        $array["vesselName"] = $vessel->getName();
        $array["buildingLevel"] = $number;
        $array["vesselNumber"] = $number;
        $array["buildingNeededResources"] = $resources;
        $array["vesselNeededResources"] = $resources;
        return $array;
    }
}