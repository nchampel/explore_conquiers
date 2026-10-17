<?php

namespace App\Entity;

use App\Repository\PlanetResourceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetResourceRepository::class)]
class PlanetResource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'planetResources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planet $planet = null;

    #[ORM\Column]
    private ?int $metal = null;

    #[ORM\Column]
    private ?int $crystal = null;

    #[ORM\Column]
    private ?int $deuterium = 0;

    #[ORM\Column]
    private ?int $satellite = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanet(): ?Planet
    {
        return $this->planet;
    }

    public function setPlanet(?Planet $planet): static
    {
        $this->planet = $planet;

        return $this;
    }

    public function getMetal(): ?int
    {
        return $this->metal;
    }

    public function setMetal(int $metal): static
    {
        $this->metal = $metal;

        return $this;
    }

    public function getCrystal(): ?int
    {
        return $this->crystal;
    }

    public function setCrystal(int $crystal): static
    {
        $this->crystal = $crystal;

        return $this;
    }

    public function getDeuterium(): ?int
    {
        return $this->deuterium;
    }

    public function setDeuterium(int $deuterium): static
    {
        $this->deuterium = $deuterium;

        return $this;
    }

    public function getSatellite(): ?int
    {
        return $this->satellite;
    }

    public function setSatellite(int $satellite): static
    {
        $this->satellite = $satellite;

        return $this;
    }
}
