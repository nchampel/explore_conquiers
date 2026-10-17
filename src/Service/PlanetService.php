<?php

namespace App\Service;

use App\Entity\Planet;
use Doctrine\ORM\EntityManagerInterface;

class PlanetService
{
    public function __construct(private EntityManagerInterface $entity, private ConstantService $constantService){
        // $this->entity = $entity;
    }
    private function chainedCompareEqual(int $min, int $max, int $value): bool{
        if($min <= $value && $value <= $max){
            return true;
        } else {
            return false;
        }
    }
    private function generateFields(int $position): int{
        //         80%
        // 1 à 3 	95-137
        // 4 à 6 	123-226
        // 7 à 9 	141-246
        // 10 à 12 	136-217
        // 13 à 15 	65-120 
        $minFields = 0;
        $maxFields = 0;
        switch($position){
            case 1:
            case 2:
            case 3:
                $minFields = 95;
                $maxFields = 137;
                break;
            case 4:
            case 5:
            case 6:
                $minFields = 123;
                $maxFields = 226;
                break;
            case 7:
            case 8:
            case 9:
                $minFields = 141;
                $maxFields = 246;
                break;
            case 10:
            case 11:
            case 12:
                $minFields = 136;
                $maxFields = 217;
                break;
            case 13:
            case 14:
            case 15:
                $minFields = 65;
                $maxFields = 120;
                break;
        }
        $alea = random_int(1, 5);
        if($alea != 1){
            return random_int($minFields, $maxFields);
        } else {
            $alea2 = random_int(0, 1);
            if($alea2 == 0){
                return random_int(1, $minFields - 1);
            } else {

                return random_int($maxFields + 1, 260);
            }
        }
    }

    private function generateTemperature(int $position): int{
        // 1 à 3 	80-120
        // 4 à 6 	60-80
        // 7 à 9 	50-60
        // 10 à 12 	40-50
        // 13 à 15 	30-40
        $minTemperature = 0;
        $maxTemperature = 0;
        switch($position){
            case 1:
            case 2:
            case 3:
                $minTemperature = 110;
                $maxTemperature = 140;
                break;
            case 4:
            case 5:
            case 6:
                $minTemperature = 70;
                $maxTemperature = 100;
                break;
            case 7:
            case 8:
            case 9:
                $minTemperature = 30;
                $maxTemperature = 60;
                break;
            case 10:
            case 11:
            case 12:
                $minTemperature = -10;
                $maxTemperature = 20;
                break;
            case 13:
            case 14:
            case 15:
                $minTemperature = -50;
                $maxTemperature = -20;
                break;
        }
        return random_int($minTemperature, $maxTemperature);

    }

    private function getSatelliteProduction(int $temperature): int{
        return round(($temperature / 4 + 20) /60);
    }

    private function getDeuteriumProductionPerMinute(int $temperature, int $refineryDeuteriumLevel): int{
        return round(((10 * $refineryDeuteriumLevel * 1.1 ** $refineryDeuteriumLevel) * (1.28 - (0.002 * $temperature))) / 60) * $this->constantService->getSpeedGame()->getValue();
    }

    public function generatePlanets(int $galaxy){
        // $maxSystem = 499;
        $maxSystem = 50;
        for($system = 1 ; $system <= $maxSystem ; $system++){
            for($position = 1 ; $position <= 15 ; $position++){
                // $alea = random_int(1, 4);
                // if($alea != 1){
                    // if($this->chainedCompareEqual(1, 3, $position)){
                        // $aleaFinal = random_int(1, 4);
                        // if($aleaFinal == 1){
                            $fields = $this->generateFields($position);
                            $temperature = $this->generateTemperature($position);

                            $planet = new Planet();
                            $planet->setFields($fields);
                            $planet->setGalaxy($galaxy);
                            $planet->setGSystem($system);
                            $planet->setPosition($position);
                            $planet->setTemperature($temperature);

                            $this->entity->persist($planet);

                            // return ["fields" => $fields, "temperature" => $temperature];
                //         }
                //     }
                // }
            }
        }
        $this->entity->flush();
    }

    public function generateAIPlanets(int $galaxy){
        // les if créés pour rendre les planètes occupées aléatoirement
        // for($system = 1 ; $system <= 499 ; $system++){
        //     for($position = 1 ; $position <= 15 ; $position++){
        //         $alea = random_int(1, 4);
        //         if($alea != 1){
        //             if($this->chainedCompareEqual(1, 3, $position)){
        //                 $aleaFinal = random_int(1, 4);
        //                 if($aleaFinal == 1){
        //                     $fields = $this->generateFields($position);
        //                     $temperature = $this->generateTemperature($position);

        //                     $planet = new Planet();
        //                     $planet->setFields($fields);
        //                     $planet->setGalaxy($galaxy);
        //                     $planet->setGSystem($system);
        //                     $planet->setPosition($position);
        //                     $planet->setTemperature($temperature);
                            // mettre user IA

        //                     // return ["fields" => $fields, "temperature" => $temperature];
        //                 }
        //             }
        //         }
        //     }
    }
}