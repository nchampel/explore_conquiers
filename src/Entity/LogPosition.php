<?php

namespace App\Entity;

use App\Repository\LogPositionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogPositionRepository::class)]
class LogPosition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'logPositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'logPositionsStart')]
    private ?GpsPoint $startPlanet = null;

    #[ORM\ManyToOne(inversedBy: 'logPositionsDestination')]
    private ?GpsPoint $destinationGpsPoint = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $longitude = null;

    #[ORM\Column]
    private ?float $accuracy = null;

    #[ORM\Column]
    private ?float $speed = null;

    #[ORM\Column]
    private ?float $stepDistance = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

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

    public function getStartPlanet(): ?GpsPoint
    {
        return $this->startPlanet;
    }

    public function setStartPlanet(?GpsPoint $startPlanet): static
    {
        $this->startPlanet = $startPlanet;

        return $this;
    }

    public function getDestinationGpsPoint(): ?GpsPoint
    {
        return $this->destinationGpsPoint;
    }

    public function setDestinationGpsPoint(?GpsPoint $destinationGpsPoint): static
    {
        $this->destinationGpsPoint = $destinationGpsPoint;

        return $this;
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

    public function getAccuracy(): ?float
    {
        return $this->accuracy;
    }

    public function setAccuracy(float $accuracy): static
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getSpeed(): ?float
    {
        return $this->speed;
    }

    public function setSpeed(float $speed): static
    {
        $this->speed = $speed;

        return $this;
    }

    public function getStepDistance(): ?float
    {
        return $this->stepDistance;
    }

    public function setStepDistance(float $stepDistance): static
    {
        $this->stepDistance = $stepDistance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
