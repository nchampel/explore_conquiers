<?php

namespace App\Service;

use App\Entity\Planet;
use App\Repository\PlanetBuildingRepository;

class BuildingService
{
    public function __construct(private PlanetBuildingRepository $planetBuildingRepository) {
    }
    public function getBuildingsCountByPlanet(Planet $planet): int{
        $buildings = $this->planetBuildingRepository->findBy(["planet" => $planet]);
        $count = 0;
        foreach($buildings as $building) {
            $count += $building->getLevel();
        }
        return $count;
    }
}