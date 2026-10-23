<?php

namespace App\Service;

use App\Entity\Building;
use App\Entity\Defence;
use App\Entity\Planet;
use App\Entity\Research;
use App\Entity\User;
use App\Entity\Vessel;

class TechnologyService
{
    // public function __construct(private PlanetBuildingRepository $planetBuildingRepository) {
    // }
    /**
     * @param \App\Entity\User $user
     */
    public function isPrerequisitesMet(User $user, Planet $planet, Vessel|Research|Building|Defence $entity, string $type): array{
        $result = true;
        $prerequisitesList = $this->prerequisitesList($entity, $type);

        if(!$prerequisitesList){
            return ["hasPrerequisites" => false, "prerequisitesList" => [], "isUnlocked" => "on"];
        }

        foreach($prerequisitesList as $element){
            switch($element["type"]){
                case "building":
                    $result &= $planet->getBuildingByName($element["name"])->getLevel() >= $element["level"];
                    break;
                case "research":
                    $result &= $user->getResearchByName($element["name"])->getLevel() >= $element["level"];
                    break;
            }
        }
        
        return ["isPrerequisitesMet" => $result, "prerequisitesList" => $prerequisitesList, "hasPrerequisites" => true, "isUnlocked" => $result ? "on" : "off"];
    }

    private function prerequisitesList(Vessel|Research|Building|Defence $entity, string $type): array|bool{
        // $array = [];
        switch($type){
            case "building":
                switch($entity->getId()){
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 6:
                    case 7:
                    case 8:
                        return false;
                    case 5:
                        return [
                                ["type" => "building", "name" => "Synthétiseur de deutérium", "level" => 5],
                                ["type" => "building", "name" => "Laboratoire de recherche", "level" => 1],
                                ["type" => "research", "name" => "Technologie énergétique", "level" => 3],
                        ];
                    default:
                        return [];
                }
            case "vessel":
                switch($entity->getId()){
                    case 14:
                        return [
                                ["type" => "building", "name" => "Usine de robots", "level" => 2],
                                ["type" => "building", "name" => "Chantier spatial", "level" => 1],
                                // ["type" => "research", "name" => "Technologie énergétique", "level" => 3],
                        ];
                    
                        
                    default:
                        return [];
                }
            default:    
                return [];
        }
    }
}