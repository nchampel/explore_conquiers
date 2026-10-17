<?php

namespace App\Entity;

use App\Repository\ResearchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResearchRepository::class)]
class Research
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

    #[ORM\OneToMany(mappedBy: 'research', targetEntity: ResearchPlayer::class, orphanRemoval: true)]
    private Collection $researchPlayers;

    public function __construct()
    {
        $this->researchPlayers = new ArrayCollection();
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
     * @return Collection<int, ResearchPlayer>
     */
    public function getResearchPlayers(): Collection
    {
        return $this->researchPlayers;
    }

    public function addResearchPlayer(ResearchPlayer $researchPlayer): static
    {
        if (!$this->researchPlayers->contains($researchPlayer)) {
            $this->researchPlayers->add($researchPlayer);
            $researchPlayer->setResearch($this);
        }

        return $this;
    }

    public function removeResearchPlayer(ResearchPlayer $researchPlayer): static
    {
        if ($this->researchPlayers->removeElement($researchPlayer)) {
            // set the owning side to null (unless already changed)
            if ($researchPlayer->getResearch() === $this) {
                $researchPlayer->setResearch(null);
            }
        }

        return $this;
    }
}
