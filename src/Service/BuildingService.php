<?php

namespace App\Service;

use App\Entity\Building;
use App\Entity\Planet;
use App\Entity\PlanetBuilding;
use App\Entity\PlanetVessel;
use App\Repository\PlanetBuildingRepository;
use Psr\Log\LoggerInterface;

class BuildingService
{
    public function __construct(private PlanetBuildingRepository $planetBuildingRepository, private LoggerInterface $logger, private ConstantService $constantService) {}
    public function getBuildingsCountByPlanet(Planet $planet): int
    {
        $buildings = $this->planetBuildingRepository->findBy(["planet" => $planet]);
        $count = 0;
        foreach ($buildings as $building) {
            $count += $building->getLevel();
        }
        return $count;
    }
    public function getTechnoIDCorrespondence(int $technology): int
    {
        switch ($technology) {
            case 1:
                // mine de métal
                return 1;
            case 2:
                // mine de cristal
                return 2;
            case 3:
                // synthétiseur de deutérium
                return 3;
            case 4:
                // centrale électrique solaire
                return 4;
            case 12:
                // centrale électrique de fusion
                return 5;
            case 22:
                // hangar de métal
                return 6;
            case 23:
                // hangar de cristal
                return 7;
            case 24:
                // réservoir de deutérium
                return 8;
        }
        return 0;
    }
    public function getDescription(int $buildingId): string
    {
        switch ($buildingId) {
            case 1:
                // mine de métal
                return "Principale ressource dans la construction de bâtiments et de vaisseaux.";
            case 2:
                // mine de cristal
                return "Principale ressource pour les installations électroniques et pour les alliages.";
            case 3:
                // synthétiseur de deutérium
                return "Extrait la petite quantité de deutérium de l`eau d`une planète.";
            case 4:
                // centrale électrique solaire
                return "Les centrales électriques solaires transforment les rayons de soleil en énergie. Presque tous les bâtiments ont besoin d`énergie pour fonctionner.";
            case 5:
                // centrale électrique de fusion
                return "La centrale électrique de fusion produit de l`énergie en fusionnant 2 atomes d`hydrogène en un atome d`hélium.";
            case 6:
                // hangar de métal
                return "Hangar pour minerai avant le traitement.";
            case 7:
                // hangar de cristal
                return "Hangar pour cristal avant le traitement.";
            case 8:
                // réservoir de deutérium
                return "Réservoirs géants pour le stockage de deutérium.";
        }
        return 0;
    }
    public function getFactorNeededResource(int $buildingId): float
    {
        switch ($buildingId) {
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
    public function getFactorConsumption(int $buildingId): float
    {
        switch ($buildingId) {
            case 1:
                // mine de métal
                return 10;
            case 2:
                // mine de cristal
                return 10;
            case 3:
                // synthétiseur de deutérium
                return 20;
            case 5:
                // centrale électrique de fusion
                return 10;
        }
        return 0;
    }
    public function getEnergyType(int $buildingId): string
    {
        switch ($buildingId) {
            case 1:
            case 2:
            case 3:
                // synthétiseur de deutérium
                return "consumption";
            case 4:
            case 5:
                // centrale électrique de fusion
                return "prod";
            default:
                return "energy not involved";
        }
    }
    public function getResourcesToBuild(int $level, Building $building): array
    {
        $array = [];
        $factor = $this->getFactorNeededResource($building->getId());
        $array["metal"] = floor($building->getMetal() * $factor ** ($level + 1 - 1));
        $array["crystal"] = floor($building->getCrystal() * $factor ** ($level + 1 - 1));
        $array["deuterium"] = floor($building->getDeuterium() * $factor ** ($level + 1 - 1));
        return $array;
    }
    public function getConsumptionBuilding(int $level, Building $building): int
    {
        return $this->getFactorConsumption($building->getId()) * ($level) * 1.1 ** ($level);
    }
    public function getNextConsumptionBuilding(int $level, Building $building): int
    {
        return $this->getFactorConsumption($building->getId()) * ($level + 1) * 1.1 ** ($level + 1);
    }
    private function getFormattedTime(int $seconds)
    {
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
    public function getTimeToBuild(array $neededResources, Planet $planet): array
    {
        // $array = [];
        // $factor = $this->getFactorNeededResource($building->getId());
        // $array["metal"] = floor($building->getMetal() * $factor ** ($level + 1 - 1));
        // $array["crystal"] = floor($building->getCrystal() * $factor ** ($level + 1 - 1));
        // $array["deuterium"] = floor($building->getDeuterium() * $factor ** ($level + 1 - 1));

        $time = floor(($neededResources["metal"] + $neededResources["crystal"]) / 2500 * 1 / (1 + $planet->getBuildingByName("Usine de robots")->getLevel()) * 0.5 ** $planet->getBuildingByName("Usine de nanites")->getLevel() * 3600 / $this->constantService->getSpeedGame());
        $timeFormatted = $this->getFormattedTime($time);
        return ["buildingTime" => $time, "buildingTimeFormatted" => $timeFormatted];
    }
    public function getStorageCapacity(int $level): int
    {

        $capacity = 5000 * floor(2.5 * 2.71828 ** (20 * $level / 33));
        return $capacity;
    }

    // private function get
    public function getTechnologiesSupplies(int $technologyId, Planet $planet): array
    {
        $array = [];
        $buildingId = $this->getTechnoIDCorrespondence($technologyId);
        // $planetBuilding = $planet->getBuildingByName("Mine de cristal");
        // $this->logger->info($buildingId);
        $planetBuilding = $planet->getBuildingById($buildingId);
        $building = $planetBuilding->getBuilding();

        $neededResources = $this->getResourcesToBuild($planetBuilding->getLevel(), $building);
        $time = $this->getTimeToBuild($neededResources, $planet);

        // $neededMetal = $building->getMetal();
        // $neededResources = ["metal" => $neededMetal, "crystal"  => 0, "deuterium" => 0];
        switch ($buildingId) {
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
        $array["buildingLevel"] = $planetBuilding->getLevel();
        $array["buildingNeededResources"] = $neededResources;
        $array["buildingTime"] = $time["buildingTime"];
        $array["buildingTimeFormatted"] = $time["buildingTimeFormatted"];
        $array["buildingConsumption"] = $this->getConsumptionBuilding($planetBuilding->getLevel(), $building);
        $array["buildingNextConsumption"] = $this->getNextConsumptionBuilding($planetBuilding->getLevel(), $building);
        $array["description"] = $this->getDescription($buildingId);
        $array["storageCapacity"] = $this->getStorageCapacity($planetBuilding->getLevel());
        return $array;
    }
    private function getBuildingTime(PlanetBuilding $planetBuilding){
        $level = $planetBuilding->getLevel();
    }
    public function buildBuilding(PlanetBuilding $planetBuilding, Planet $planet)
    {
        $planetResource = $planet->getPlanetResource();
        $neededResources = $this->getResourcesToBuild($planetBuilding->getLevel(), $planetBuilding->getBuilding());
        $time = $this->getTimeToBuild($neededResources, $planet);
        $metal = $planetResource->getMetal();
        $crystal = $planetResource->getCrystal();
        $deuterium =$planetResource->getDeuterium();
        if (
            $neededResources["metal"] > $metal ||
            $neededResources["crystal"] > $crystal ||
            $neededResources["deuterium"] > $deuterium
        ) {
            return ["msg" => "pas assez de ressources pour construire", "buildingTime" => null];
        }
        $planetResource->setMetal($metal - $neededResources["metal"]);
        $planetResource->setCrystal($crystal - $neededResources["crystal"]);
        $planetResource->setDeuterium($deuterium - $neededResources["deuterium"]);
        $planetBuilding->setLevel($planetBuilding->getLevel() + 1);

        return ["msg" => "construction en cours", "buildingTime" => $time];

    }
}
