<?php

namespace App\Service;

use App\Repository\ConstantRepository;

class ConstantService
{
    public function __construct(private ConstantRepository $constantRepository) {
    }
    public function getSpeedGame(): int{
        return $this->constantRepository->findOneBy(["name" => "vitesse"])->getValue();
    }
}