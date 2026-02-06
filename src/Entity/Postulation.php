<?php

namespace App\Entity;

use App\Repository\PostulationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostulationRepository::class)]
class Postulation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private Candidat $candidat;

    #[ORM\ManyToOne]
    private OffreEmploi $offre;

    #[ORM\Column]
    private \DateTimeImmutable $datePostulation;

    #[ORM\Column]
    private string $statut;

    #[ORM\Column(type: 'text')]
    private string $motivationCandidature;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCandidat(): Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(Candidat $candidat): void
    {
        $this->candidat = $candidat;
    }

    public function getOffre(): OffreEmploi
    {
        return $this->offre;
    }

    public function setOffre(OffreEmploi $offre): void
    {
        $this->offre = $offre;
    }

    public function getDatePostulation(): \DateTimeImmutable
    {
        return $this->datePostulation;
    }

    public function setDatePostulation(\DateTimeImmutable $datePostulation): void
    {
        $this->datePostulation = $datePostulation;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getMotivationCandidature(): string
    {
        return $this->motivationCandidature;
    }

    public function setMotivationCandidature(string $motivationCandidature): void
    {
        $this->motivationCandidature = $motivationCandidature;
    }



}
