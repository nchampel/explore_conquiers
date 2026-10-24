<?php

namespace App\Entity;

use App\Repository\PlanetVesselRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetVesselRepository::class)]
class PlanetVessel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'planetVessels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planet $planet = null;

    #[ORM\ManyToOne(inversedBy: 'planetVessels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vessel $vessel = null;

    #[ORM\Column]
    private ?int $number = 0;

    #[ORM\Column]
    private ?bool $isUnlocked = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $constructionEndsAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVessel(): ?Vessel
    {
        return $this->vessel;
    }

    public function setVessel(?Vessel $vessel): static
    {
        $this->vessel = $vessel;

        return $this;
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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function isIsUnlocked(): ?bool
    {
        return $this->isUnlocked;
    }

    public function setIsUnlocked(bool $isUnlocked): static
    {
        $this->isUnlocked = $isUnlocked;

        return $this;
    }

    public function getConstructionEndsAt(): ?\DateTimeInterface
    {
        return $this->constructionEndsAt;
    }

    public function setConstructionEndsAt(?\DateTimeInterface $constructionEndsAt): static
    {
        $this->constructionEndsAt = $constructionEndsAt;

        return $this;
    }
}
