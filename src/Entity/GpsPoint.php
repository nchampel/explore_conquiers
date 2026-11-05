<?php

namespace App\Entity;

use App\Repository\GpsPointRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GpsPointRepository::class)]
class GpsPoint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['planet:read'])]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'gpsPoint', targetEntity: Planet::class)]
    private Collection $planet;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    #[Groups(['planet:read'])]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    #[Groups(['planet:read'])]
    private ?string $longitude = null;

    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[ORM\Column]
    #[Groups(['planet:read'])]
    private ?int $activationRadius = null;

    #[ORM\Column]
    #[Groups(['planet:read'])]
    private ?bool $isVisitedForDailyQuest = null;

    #[ORM\OneToMany(mappedBy: 'startPlanet', targetEntity: LogPosition::class)]
    private Collection $logPositionsStart;

    #[ORM\OneToMany(mappedBy: 'destinationGpsPoint', targetEntity: LogPosition::class)]
    private Collection $logPositionsDestination;

    public function __construct()
    {
        $this->planet = new ArrayCollection();
        $this->logPositionsStart = new ArrayCollection();
        $this->logPositionsDestination = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getActivationRadius(): ?int
    {
        return $this->activationRadius;
    }

    public function setActivationRadius(int $activationRadius): static
    {
        $this->activationRadius = $activationRadius;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Planet>
     */
    public function getPlanet(): Collection
    {
        return $this->planet;
    }

    public function addPlanet(Planet $planet): static
    {
        if (!$this->planet->contains($planet)) {
            $this->planet->add($planet);
            $planet->setGpsPoint($this);
        }

        return $this;
    }

    public function removePlanet(Planet $planet): static
    {
        if ($this->planet->removeElement($planet)) {
            // set the owning side to null (unless already changed)
            if ($planet->getGpsPoint() === $this) {
                $planet->setGpsPoint(null);
            }
        }

        return $this;
    }

    public function isVisitedForDailyQuest(): ?bool
    {
        return $this->isVisitedForDailyQuest;
    }

    public function setIsVisitedForDailyQuest(bool $isVisitedForDailyQuest): static
    {
        $this->isVisitedForDailyQuest = $isVisitedForDailyQuest;

        return $this;
    }

    /**
     * @return Collection<int, LogPosition>
     */
    public function getLogPositionsStart(): Collection
    {
        return $this->logPositionsStart;
    }

    public function addLogPositionStart(LogPosition $logPositionStart): static
    {
        if (!$this->logPositionsStart->contains($logPositionStart)) {
            $this->logPositionsStart->add($logPositionStart);
            $logPositionStart->setStartPlanet($this);
        }

        return $this;
    }

    public function removeLogPositionStart(LogPosition $logPositionStart): static
    {
        if ($this->logPositionsStart->removeElement($logPositionStart)) {
            // set the owning side to null (unless already changed)
            if ($logPositionStart->getStartPlanet() === $this) {
                $logPositionStart->setStartPlanet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LogPosition>
     */
    public function getLogPositionsDestination(): Collection
    {
        return $this->logPositionsDestination;
    }

    public function addLogPositionsDestination(LogPosition $logPositionsDestination): static
    {
        if (!$this->logPositionsDestination->contains($logPositionsDestination)) {
            $this->logPositionsDestination->add($logPositionsDestination);
            $logPositionsDestination->setDestinationGpsPoint($this);
        }

        return $this;
    }

    public function removeLogPositionsDestination(LogPosition $logPositionsDestination): static
    {
        if ($this->logPositionsDestination->removeElement($logPositionsDestination)) {
            // set the owning side to null (unless already changed)
            if ($logPositionsDestination->getDestinationGpsPoint() === $this) {
                $logPositionsDestination->setDestinationGpsPoint(null);
            }
        }

        return $this;
    }
}
