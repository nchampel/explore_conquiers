<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
class Building
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
    private ?int $deuterium = 0;

    #[ORM\OneToMany(mappedBy: 'building', targetEntity: PlanetBuilding::class, orphanRemoval: true)]
    private Collection $planetBuildings;

    public function __construct()
    {
        $this->planetBuildings = new ArrayCollection();
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
     * @return Collection<int, PlanetBuilding>
     */
    public function getPlanetBuildings(): Collection
    {
        return $this->planetBuildings;
    }

    public function addPlanetBuilding(PlanetBuilding $planetBuilding): static
    {
        if (!$this->planetBuildings->contains($planetBuilding)) {
            $this->planetBuildings->add($planetBuilding);
            $planetBuilding->setBuilding($this);
        }

        return $this;
    }

    public function removePlanetBuilding(PlanetBuilding $planetBuilding): static
    {
        if ($this->planetBuildings->removeElement($planetBuilding)) {
            // set the owning side to null (unless already changed)
            if ($planetBuilding->getBuilding() === $this) {
                $planetBuilding->setBuilding(null);
            }
        }

        return $this;
    }

}
