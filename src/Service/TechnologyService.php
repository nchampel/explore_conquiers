<?php

namespace App\Service;

class TechonologyService
{
    // public function __construct(private PlanetBuildingRepository $planetBuildingRepository) {
    // }
    public function getTechonologiesSupplies(int $technologyId): array{
        $array = [];
        switch($technologyId){
            case 2:
                $array["buildingNameEn"] = "crystalMine";
                $array["buildingName"] = "Mine de cristal";
                $array["buttonTitle"] = "Aucune condition requise";
        }
        return $array;
    }
}