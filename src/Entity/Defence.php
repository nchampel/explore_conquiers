<?php

namespace App\Entity;

use App\Repository\DefenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DefenceRepository::class)]
class Defence
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

    #[ORM\OneToMany(mappedBy: 'defence', targetEntity: PlanetDefence::class, orphanRemoval: true)]
    private Collection $planetDefences;

    public function __construct()
    {
        $this->planetDefences = new ArrayCollection();
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
     * @return Collection<int, PlanetDefence>
     */
    public function getPlanetDefences(): Collection
    {
        return $this->planetDefences;
    }

    public function addPlanetDefence(PlanetDefence $planetDefence): static
    {
        if (!$this->planetDefences->contains($planetDefence)) {
            $this->planetDefences->add($planetDefence);
            $planetDefence->setDefence($this);
        }

        return $this;
    }

    public function removePlanetDefence(PlanetDefence $planetDefence): static
    {
        if ($this->planetDefences->removeElement($planetDefence)) {
            // set the owning side to null (unless already changed)
            if ($planetDefence->getDefence() === $this) {
                $planetDefence->setDefence(null);
            }
        }

        return $this;
    }
}
