<?php

namespace App\Entity;

use App\Repository\PlanetDefenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetDefenceRepository::class)]
class PlanetDefence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'planetDefences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planet $planet = null;

    #[ORM\ManyToOne(inversedBy: 'planetDefences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Defence $defence = null;

    #[ORM\Column]
    private ?int $number = 0;

    #[ORM\Column]
    private ?bool $isUnlocked = false;

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

    public function getDefence(): ?Defence
    {
        return $this->defence;
    }

    public function setDefence(?Defence $defence): static
    {
        $this->defence = $defence;

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
}
