<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $titre;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private int $duree;

    #[ORM\Column]
    private string $niveau;

    #[ORM\Column(type: 'json')]
    private array $competencesVisees = [];

    #[ORM\Column]
    private bool $estObligatoire = false;

    #[ORM\ManyToMany(targetEntity: Mission::class, inversedBy: 'cours')]
    #[ORM\JoinTable(name: 'cours_mission')]
    private Collection $missions;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function getNiveau(): string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): void
    {
        $this->niveau = $niveau;
    }

    public function getCompetencesVisees(): array
    {
        return $this->competencesVisees;
    }

    public function setCompetencesVisees(array $competencesVisees): void
    {
        $this->competencesVisees = $competencesVisees;
    }

    public function isEstObligatoire(): bool
    {
        return $this->estObligatoire;
    }

    public function setEstObligatoire(bool $estObligatoire): void
    {
        $this->estObligatoire = $estObligatoire;
    }

    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function setMissions(Collection $missions): void
    {
        $this->missions = $missions;
    }

    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function setCandidats(Collection $candidats): void
    {
        $this->candidats = $candidats;
    }

}
