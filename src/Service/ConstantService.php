<?php

namespace App\Service;

use App\Entity\Constant;
use App\Repository\ConstantRepository;

class ConstantService
{
    public function __construct(private ConstantRepository $constantRepository) {
    }
    public function getSpeedGame(): Constant{
        return $this->constantRepository->findOneBy(["name" => "vitesse"]);
    }
}