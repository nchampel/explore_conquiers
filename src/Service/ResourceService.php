<?php

namespace App\Service;

use App\Entity\Planet;
use App\Entity\PlanetBuilding;
use App\Entity\PlanetResource;
use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetResourceRepository;
use Doctrine\ORM\EntityManagerInterface;

class ResourceService
{
    public function __construct(private EntityManagerInterface $entity, private PlanetResourceRepository $planetResourceRepository, private PlanetBuildingRepository $planetBuildingRepository){
        // $this->entity = $entity;
    }

    private function getMetalProduction(PlanetBuilding $building){
        $buildingLevel = $building->getLevel();
        return 4 + round(4 * $buildingLevel * 1.1 ** $buildingLevel);
    }
    private function getCrystalProduction(PlanetBuilding $building){
        $buildingLevel = $building->getLevel();
        return 2 + round(2 * $buildingLevel * 1.1 ** $buildingLevel);
    }
    private function getDeuteriumProduction(PlanetBuilding $building, Planet $planet){
        $buildingLevel = $building->getLevel();
        return round((87 * $buildingLevel * 1.1 ** $buildingLevel * (1.28 - 0.002 * $planet->getTemperature())) / 60);
    }

    private function addMetal(PlanetBuilding $building, PlanetResource $resources){
        $metal = $resources->getMetal();
        // $buildingLevel = $this->planetBuildingRepository->findOneBy(["planet" => $planet, "building" => 1])->getLevel();       
        // return $metal + 4 + round(4 * $buildingLevel * 1.1 ** $buildingLevel);
        $resources->setMetal($metal + $this->getMetalProduction($building));
        // 120 + 
        // echo $generatedMetal;
    }
    private function addCrystal(PlanetBuilding $building, PlanetResource $resources){
        $crystal = $resources->getCrystal();
        // return $crystal + 2 + round(2 * $buildingLevel * 1.1 ** $buildingLevel);
        $resources->setCrystal($crystal + $this->getCrystalProduction($building));
    }
    private function addDeuterium(PlanetBuilding $building, PlanetResource $resources, Planet $planet){
        $deuterium = $resources->getDeuterium();
        $resources->setDeuterium($deuterium + $this->getDeuteriumProduction($building, $planet));
    }

    /**
     * appelé toutes les minutes par le serveur
     * @param \App\Entity\PlanetResource $resources
     * @param \App\Entity\PlanetBuilding[] $buildings
     */
    public function addResource(string $resource, Planet $planet, PlanetResource $resources, array $buildings){
        switch($resource){
            case "metal":
                $this->addMetal($buildings[0], $resources);
                break;
            case "crystal":
                $this->addCrystal($buildings[1], $resources);
                break;
            case "deuterium":
                $this->addDeuterium($buildings[2], $resources, $planet);
                break;
        }
    }

    // private function incrementMetal(PlanetBuilding $building){
    //     return $this->getMetalProduction($building);
    // }
    // private function incrementCrystal(PlanetBuilding $building){
    //     return $this->getCrystalProduction($building);
    // }
    // private function incrementDeuterium(PlanetBuilding $building, Planet $planet){
    //     return $this->getDeuteriumProduction($building, $planet);
    // }

    public function incrementResource(string $resource, Planet $planet, array $buildings){
        switch($resource){
            case "metal":
                return $this->getMetalProduction($buildings[0]);
                // return $this->incrementMetal($buildings[0], $resources);
                break;
            case "crystal":
                return $this->getCrystalProduction($buildings[1]);
                // return $this->incrementCrystal($buildings[1], $resources);
                break;
            case "deuterium":
                return $this->getDeuteriumProduction($buildings[2], $planet);
                // return $this->incrementDeuterium($buildings[2], $resources, $planet);
                break;
        }
    }

}