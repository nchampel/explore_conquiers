<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['pseudo'], message: 'There is already an account with this pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PlanetBuilding::class)]
    private Collection $planetBuildings;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Planet::class)]
    private Collection $planets;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ResearchPlayer::class, orphanRemoval: true)]
    private Collection $researchesPlayer;

    public function __construct()
    {
        $this->planetBuildings = new ArrayCollection();
        $this->planets = new ArrayCollection();
        $this->researchesPlayer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['user:read'])]
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            // $planetBuilding->setUser($this);
        }

        return $this;
    }

    public function removePlanetBuilding(PlanetBuilding $planetBuilding): static
    {
        if ($this->planetBuildings->removeElement($planetBuilding)) {
            // set the owning side to null (unless already changed)
            // if ($planetBuilding->getUser() === $this) {
            //     $planetBuilding->setUser(null);
            // }
        }

        return $this;
    }

    /**
     * @return Collection<int, Planet>
     */
    public function getPlanets(): Collection
    {
        return $this->planets;
    }

    public function addPlanet(Planet $planet): static
    {
        if (!$this->planets->contains($planet)) {
            $this->planets->add($planet);
            $planet->setUser($this);
        }

        return $this;
    }

    public function removePlanet(Planet $planet): static
    {
        if ($this->planets->removeElement($planet)) {
            // set the owning side to null (unless already changed)
            if ($planet->getUser() === $this) {
                $planet->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ResearchPlayer>
     */
    public function getResearchesPlayer(): Collection
    {
        return $this->researchesPlayer;
    }

    public function addResearchPlayer(ResearchPlayer $researchPlayer): static
    {
        if (!$this->researchesPlayer->contains($researchPlayer)) {
            $this->researchesPlayer->add($researchPlayer);
            $researchPlayer->setUser($this);
        }

        return $this;
    }

    public function removeResearchPlayer(ResearchPlayer $researchPlayer): static
    {
        if ($this->researchesPlayer->removeElement($researchPlayer)) {
            // set the owning side to null (unless already changed)
            if ($researchPlayer->getUser() === $this) {
                $researchPlayer->setUser(null);
            }
        }

        return $this;
    }

    public function getResearchByName(string $name): ?ResearchPlayer // Adapte le nom de ton entité de liaison
    {
        foreach ($this->researchesPlayer as $researchPlayer) {
            if ($researchPlayer->getResearch()->getName() === $name) {
                return $researchPlayer;
            }
        }

        return null; // Retourne null si le bâtiment n'est pas encore construit sur la planète
    }
}
