<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private int $scoreMin = 60;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToMany(mappedBy: 'mission', targetEntity: RenduMission::class)]
    private Collection $rendus;

    #[ORM\ManyToMany(targetEntity: Candidat::class, mappedBy: 'missions')]
    private Collection $candidats;

    #[ORM\ManyToMany(targetEntity: Cours::class, mappedBy: 'missions')]
    private Collection $cours;

    public function __construct()
    {
        $this->rendus = new ArrayCollection();
        $this->candidats = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getScoreMin(): int
    {
        return $this->scoreMin;
    }

    public function setScoreMin(int $scoreMin): void
    {
        $this->scoreMin = $scoreMin;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getRendus(): Collection
    {
        return $this->rendus;
    }

    public function setRendus(Collection $rendus): void
    {
        $this->rendus = $rendus;
    }

    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function setCandidats(Collection $candidats): void
    {
        $this->candidats = $candidats;
    }

    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function setCours(Collection $cours): void
    {
        $this->cours = $cours;
    }



}
