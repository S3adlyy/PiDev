<?php

namespace App\Entity;

use App\Repository\RenduMissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RenduMissionRepository::class)]
class RenduMission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rendus')]
    #[ORM\JoinColumn(nullable: false)]
    private Mission $mission;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Candidat $candidat;

    #[ORM\Column]
    private string $fichier;

    #[ORM\Column]
    private \DateTimeImmutable $dateRendu;

    #[ORM\Column(nullable: true)]
    private ?float $score = null;

    #[ORM\Column(nullable: true)]
    private ?string $resultat = null;

    //#[ORM\OneToOne(mappedBy: 'rendu', targetEntity: Feedback::class, cascade: ['persist', 'remove'])]
    //private ?Feedback $feedback = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getMission(): Mission
    {
        return $this->mission;
    }

    public function setMission(Mission $mission): void
    {
        $this->mission = $mission;
    }

    public function getCandidat(): Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(Candidat $candidat): void
    {
        $this->candidat = $candidat;
    }

    public function getFichier(): string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): void
    {
        $this->fichier = $fichier;
    }

    public function getDateRendu(): \DateTimeImmutable
    {
        return $this->dateRendu;
    }

    public function setDateRendu(\DateTimeImmutable $dateRendu): void
    {
        $this->dateRendu = $dateRendu;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): void
    {
        $this->score = $score;
    }

    public function getResultat(): ?string
    {
        return $this->resultat;
    }

    public function setResultat(?string $resultat): void
    {
        $this->resultat = $resultat;
    }

    public function getFeedback(): ?Feedback
    {
        return $this->feedback;
    }

    public function setFeedback(?Feedback $feedback): void
    {
        $this->feedback = $feedback;
    }


}
