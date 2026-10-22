<?php

namespace App\Entity;

use App\Repository\PlanetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function PHPUnit\Framework\throwException;

#[ORM\Entity(repositoryClass: PlanetRepository::class)]
class Planet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'planets')]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $galaxy = null;

    #[ORM\Column]
    private ?int $gSystem = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\OneToMany(mappedBy: 'planet', targetEntity: PlanetBuilding::class, orphanRemoval: true)]
    private Collection $planetBuildings;

    #[ORM\Column]
    private ?int $temperature = null;

    // #[ORM\OneToMany(mappedBy: 'planet', targetEntity: PlanetResource::class, orphanRemoval: true)]
    // private Collection $planetResources;

    #[ORM\Column]
    private ?bool $isDestroyed = false;

    #[ORM\Column]
    private ?int $fields = null;

    #[ORM\OneToMany(mappedBy: 'planet', targetEntity: PlanetVessel::class, orphanRemoval: true)]
    private Collection $planetVessels;

    #[ORM\OneToMany(mappedBy: 'planet', targetEntity: PlanetDefence::class, orphanRemoval: true)]
    private Collection $planetDefences;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'planet', cascade: ['persist', 'remove'])]
    private ?PlanetResource $planetResource = null;

    public function __construct()
    {
        $this->planetBuildings = new ArrayCollection();
        // $this->planetResources = new ArrayCollection();
        $this->planetVessels = new ArrayCollection();
        $this->planetDefences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getGalaxy(): ?int
    {
        return $this->galaxy;
    }

    public function setGalaxy(int $galaxy): static
    {
        if($galaxy > 10){
            throw new \Exception("Il n'y a que 10 galaxies");
        }
        $this->galaxy = $galaxy;

        return $this;
    }

    public function getGSystem(): ?int
    {
        return $this->gSystem;
    }

    public function setGSystem(int $gSystem): static
    {
        if($gSystem > 499){
            throw new \Exception("Il n'y a que 499 systèmes");
        }
        $this->gSystem = $gSystem;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        if($position > 15){
            throw new \Exception("Il n'y a que 15 positions");
        }
        $this->position = $position;

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
            $planetBuilding->setPlanet($this);
        }

        return $this;
    }

    public function removePlanetBuilding(PlanetBuilding $planetBuilding): static
    {
        if ($this->planetBuildings->removeElement($planetBuilding)) {
            // set the owning side to null (unless already changed)
            if ($planetBuilding->getPlanet() === $this) {
                $planetBuilding->setPlanet(null);
            }
        }

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(int $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    // /**
    //  * @return Collection<int, PlanetResource>
    //  */
    // public function getPlanetResources(): Collection
    // {
    //     return $this->planetResources;
    // }

    // public function addPlanetResource(PlanetResource $planetResource): static
    // {
    //     if (!$this->planetResources->contains($planetResource)) {
    //         $this->planetResources->add($planetResource);
    //         $planetResource->setPlanet($this);
    //     }

    //     return $this;
    // }

    // public function removePlanetResource(PlanetResource $planetResource): static
    // {
    //     if ($this->planetResources->removeElement($planetResource)) {
    //         // set the owning side to null (unless already changed)
    //         if ($planetResource->getPlanet() === $this) {
    //             $planetResource->setPlanet(null);
    //         }
    //     }

    //     return $this;
    // }

    public function isIsDestroyed(): ?bool
    {
        return $this->isDestroyed;
    }

    public function setIsDestroyed(bool $isDestroyed): static
    {
        $this->isDestroyed = $isDestroyed;

        return $this;
    }

    public function getFields(): ?int
    {
        return $this->fields;
    }

    public function setFields(int $fields): static
    {
        $this->fields = $fields;

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
            $planetVessel->setPlanet($this);
        }

        return $this;
    }

    public function removePlanetVessel(PlanetVessel $planetVessel): static
    {
        if ($this->planetVessels->removeElement($planetVessel)) {
            // set the owning side to null (unless already changed)
            if ($planetVessel->getPlanet() === $this) {
                $planetVessel->setPlanet(null);
            }
        }

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
            $planetDefence->setPlanet($this);
        }

        return $this;
    }

    public function removePlanetDefence(PlanetDefence $planetDefence): static
    {
        if ($this->planetDefences->removeElement($planetDefence)) {
            // set the owning side to null (unless already changed)
            if ($planetDefence->getPlanet() === $this) {
                $planetDefence->setPlanet(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPlanetResource(): ?PlanetResource
    {
        return $this->planetResource;
    }

    public function setPlanetResource(PlanetResource $planetResource): static
    {
        // set the owning side of the relation if necessary
        if ($planetResource->getPlanet() !== $this) {
            $planetResource->setPlanet($this);
        }

        $this->planetResource = $planetResource;

        return $this;
    }

    public function getBuildingByName(string $name): ?PlanetBuilding // Adapte le nom de ton entité de liaison
    {
        foreach ($this->planetBuildings as $planetBuilding) {
            if ($planetBuilding->getBuilding()->getName() === $name) {
                return $planetBuilding;
            }
        }

        return null; // Retourne null si le bâtiment n'est pas encore construit sur la planète
    }
    public function getBuildingById(int $id): ?PlanetBuilding // Adapte le nom de ton entité de liaison
    {
        foreach ($this->planetBuildings as $planetBuilding) {
            if ($planetBuilding->getBuilding()->getId() === $id) {
                return $planetBuilding;
            }
        }

        return null; // Retourne null si le bâtiment n'est pas encore construit sur la planète
    }

    public function getVesselByName(string $name): ?PlanetVessel // Adapte le nom de ton entité de liaison
    {
        foreach ($this->planetVessels as $planetVessel) {
            if ($planetVessel->getVessel()->getName() === $name) {
                return $planetVessel;
            }
        }

        return null; // Retourne null si le bâtiment n'est pas encore construit sur la planète
    }
}
