<?php

namespace App\Entity;

use App\Repository\VesselRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VesselRepository::class)]
class Vessel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75)]
    private ?string $name = null;

    #[ORM\Column(length: 75)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $metal = null;

    #[ORM\Column]
    private ?int $crystal = null;

    #[ORM\Column]
    private ?int $deuterium = null;

    #[ORM\OneToMany(mappedBy: 'planet', targetEntity: PlanetVessel::class, orphanRemoval: true)]
    private Collection $planetVessels;

    public function __construct()
    {
        $this->planetVessels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    /**
     * @return Collection<int, PlanetVessel>
     */
    public function getPlanetVessels(): Collection
    {
        return $this->planetVessels;
    }

    public function addPlanetVessel(PlanetVessel $planetVessel): static
    {
        if (!$this->planetVessels->contains($planetVessel)) {
            $this->planetVessels->add($planetVessel);
            $planetVessel->setVessel($this);
        }

        return $this;
    }

    public function removePlanetVessel(PlanetVessel $planetVessel): static
    {
        if ($this->planetVessels->removeElement($planetVessel)) {
            // set the owning side to null (unless already changed)
            if ($planetVessel->getVessel() === $this) {
                $planetVessel->setVessel(null);
            }
        }

        return $this;
    }
}
