<?php

namespace App\Service;

use App\Entity\Planet;
use App\Entity\Vessel;

use App\Repository\PlanetVesselRepository;
// use Psr\Log\LoggerInterface;

class VesselService
{
    public function __construct(private PlanetVesselRepository $planetVesselRepository, private ConstantService $constantService, private PlanetService $planetService
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
            case 2:
                // chasseur lourd
                return 2;
            case 3:
                // croiseur
                return 3;
            case 4:
                // vaisseau de bataille
                return 4;
            case 12:
                // traqueur
                return 5;
            case 22:
                // bombardier
                return 6;
            case 23:
                // destructeur
                return 7;
            case 24:
                // étoile de la mort
                return 8;
            case 22:
                // petit transporteur
                return 9;
            case 23:
                // grand transporteur
                return 10;
            case 24:
                // vaisseau de colonisation
                return 11;
            case 22:
                // recycleur
                return 12;
            case 23:
                // sonde d'espionnage
                return 13;
            case 212:
                // satellite solaire
                return 14;
        }
        return 0;
    }
    public function getDescription(int $vesselId, Planet $planet): string{
        switch($vesselId){
            // case 1:
            //     // chasseur léger
            //     return 1;
            // case 2:
            //     // chasseur lourd
            //     return 2;
            // case 3:
            //     // croiseur
            //     return 3;
            // case 4:
            //     // vaisseau de bataille
            //     return 4;
            // case 5:
            //     // traqueur
            //     return 5;
            // case 6:
            //     // bombardier
            //     return 6;
            // case 7:
            //     // destructeur
            //     return 7;
            // case 8:
            //     // étoile de la mort
            //     return 8;
            // case 9:
            //     // petit transporteur
            //     return 9;
            // case 10:
            //     // grand transporteur
            //     return 10;
            // case 11:
            //     // vaisseau de colonisation
            //     return 11;
            // case 12:
            //     // recycleur
            //     return 12;
            // case 13:
            //     // sonde d'espionnage
            //     return 13;
            case 14:
                // satellite solaire
                return "Les satellites solaires sont des plates-formes couvertes de cellules solaires, 
                qui se trouvent dans une orbite très élevée et stationnaire. Ils collectent la lumière du soleil 
                et la transmettent par laser à la station de base. Un satellite solaire produit sur cette planète " . $this->planetService->getSatelliteProduction($planet->getTemperature()) . " unités d`énergie.";
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

    private function getFormattedTime(int $seconds){
        if ($seconds <= 0) {
            return '0s';
        }

        // Définition des unités en secondes
        $units = [
            'sem' => 7 * 24 * 3600, // 604 800 secondes
            'j'   => 24 * 3600,     // 86 400 secondes
            'h'   => 3600,          // 3600 secondes
            'm'   => 60,            // 60 secondes
            's'   => 1              // 1 seconde
        ];

        $parts = [];

        foreach ($units as $unitName => $unitValue) {
            // Si le nombre de secondes est supérieur ou égal à l'unité
            if ($seconds >= $unitValue) {
                $count = intdiv($seconds, $unitValue); // Division entière
                $seconds %= $unitValue;               // Reste de la division
                $parts[] = "{$count}{$unitName}";
            }
        }

        return implode(' ', $parts);

    }

    public function getTimeToBuild(array $neededResources, Planet $planet): array{
        // $array = [];
        // $factor = $this->getFactorNeededResource($building->getId());
        // $array["metal"] = floor($building->getMetal() * $factor ** ($level + 1 - 1));
        // $array["crystal"] = floor($building->getCrystal() * $factor ** ($level + 1 - 1));
        // $array["deuterium"] = floor($building->getDeuterium() * $factor ** ($level + 1 - 1));
        
        $time = floor(($neededResources["metal"] + $neededResources["crystal"]) / 5000 * 2 / (1 + $planet->getBuildingByName("Chantier spatial")->getLevel()) * 0.5 ** $planet->getBuildingByName("Usine de nanites")->getLevel() * 3600 / $this->constantService->getSpeedGame());
        $timeFormatted = $this->getFormattedTime($time);
        return ["buildingTime" => $time, "buildingTimeFormatted" => $timeFormatted];
    }
    
    // private function get
    public function getTechnologiesSupplies(int $technologyId, Planet $planet): array{
        $array = [];
        $vesselId = $this->getTechnoIDCorrespondence($technologyId);
        // $vesselPlanet = $planet->getvesselByName("Mine de cristal");
        // $this->logger->info($vesselId);
        $vesselPlanet = $planet->getVesselById($vesselId);
        $vessel = $vesselPlanet->getVessel();
        $resources = $this->getResourcesToBuild($vessel);
        $time = $this->getTimeToBuild($resources, $planet);

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
        $array["buildingName"] = $vessel->getName();
        $array["vesselName"] = $vessel->getName();
        $array["buildingLevel"] = $number;
        $array["vesselNumber"] = $number;
        $array["buildingTime"] = $time["buildingTime"];
        $array["buildingTimeFormatted"] = $time["buildingTimeFormatted"];
        $array["buildingNeededResources"] = $resources;
        $array["vesselNeededResources"] = $resources;
        $array["description"] = $this->getDescription($vesselId, $planet);
        return $array;
    }
}