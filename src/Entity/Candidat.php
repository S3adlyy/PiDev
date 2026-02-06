<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat extends User
{
    #[ORM\Column(nullable: true)]
    private ?string $headline = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(nullable: true)]
    private ?string $location = null;

    #[ORM\Column]
    private string $visibility;

    #[ORM\Column(nullable: true)]
    private ?string $niveau = null;

    #[ORM\Column(nullable: true)]
    private ?float $scoreGlobal = null;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Workspace::class)]
    private Collection $workspaces;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Postulation::class)]
    private Collection $postulations;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: RenduMission::class)]
    private Collection $rendus;

    #[ORM\ManyToMany(targetEntity: Mission::class, inversedBy: 'candidats')]
    #[ORM\JoinTable(name: 'candidat_mission')]
    private Collection $missions;


    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(string $Headline): static
    {
        $this->headline = $Headline;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $Bio): static
    {
        $this->bio = $Bio;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $Location): static
    {
        $this->location = $Location;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getScoreGlobal(): ?float
    {
        return $this->scoreGlobal;
    }

    public function setScoreGlobal(float $ScoreGlobal): static
    {
        $this->scoreGlobal = $ScoreGlobal;

        return $this;
    }
}
