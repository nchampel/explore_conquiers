<?php

namespace App\Service;

use App\Entity\Building;
use App\Entity\Defence;
use App\Entity\Planet;
use App\Entity\Research;
use App\Entity\User;
use App\Entity\Vessel;
use Psr\Log\LoggerInterface;

class TechnologyService
{
    public function __construct(private LoggerInterface $logger) {
    }
    public function checksConstructionInProgress(array $element, Planet $planet){
        // match (true) {
        //     $entity instanceof ResearchPlayer => (function() use ($element, $entity) {
        //         // Instruction 1
        //         // Instruction 2
        //         // Instruction 3
        //     })(),

        //     $entity instanceof PlanetBuilding => (function() use ($element, $entity) {
        //         $entity->getBuildingByName()
        //     })(),
        //     $entity instanceof PlanetVessel => (function() use ($element, $entity) {
        //         // Instruction 1
        //         // Instruction 2
        //     })(),
        //     $entity instanceof PlanetDefence => (function() use ($element, $entity) {
        //         // Instruction 1
        //         // Instruction 2
        //     })(),
        // };
        // foreach($element as $key => $el){
        //     $this->logger->info('clef ' . $key . ' valeur ' . $el);
        // }
        $parisTimezone = new \DateTimeZone('Europe/Paris');
        $now = new \DateTime("now", $parisTimezone);
        $constructionEndsAt = null;
        switch($element["type"]){
            case "building":
                $constructionEndsAt = $planet->getBuildingByName($element["entity"])->getConstructionEndsAt();
                break;
        }
        //  $this->logger->info($constructionEndsAt->format("d/m/Y H:i:s"));
        if(!$constructionEndsAt){
            return null;
        } else if ($constructionEndsAt >= $now) {
            return null;
        } else {
            return "construction ended";
        }
    }
    public function checksConstructionInProgressByPage(string $page, Planet $planet){
        $elements = [];
        switch($page){
            case "supplies":
                $elements = [
                    [
                        "type" => "building",
                        "entity" => "Mine de métal",
                    ],
                    [
                        "type" => "building",
                        "entity" => "Mine de cristal",
                    ],
                ];
        }
        $result = [];
        foreach($elements as $element){
            // foreach($element as $el){
                $result[$element["entity"]] = $this->checksConstructionInProgress($element, $planet);
            // }
        }
        return $result;
    }
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