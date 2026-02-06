<?php

namespace App\Entity;

use App\Repository\TraitementReclamationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraitementReclamationRepository::class)]
class TraitementReclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private Reclamation $reclamation;

    #[ORM\ManyToOne(inversedBy: 'traitements')]
    private Admin $admin;

    #[ORM\Column]
    private \DateTimeImmutable $dateTraitement;

    #[ORM\Column(type: 'text')]
    private string $reponseAdmin;

    #[ORM\Column]
    private string $statutFinal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getReclamation(): Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(Reclamation $reclamation): void
    {
        $this->reclamation = $reclamation;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }

    public function setAdmin(Admin $admin): void
    {
        $this->admin = $admin;
    }

    public function getDateTraitement(): \DateTimeImmutable
    {
        return $this->dateTraitement;
    }

    public function setDateTraitement(\DateTimeImmutable $dateTraitement): void
    {
        $this->dateTraitement = $dateTraitement;
    }

    public function getReponseAdmin(): string
    {
        return $this->reponseAdmin;
    }

    public function setReponseAdmin(string $reponseAdmin): void
    {
        $this->reponseAdmin = $reponseAdmin;
    }

    public function getStatutFinal(): string
    {
        return $this->statutFinal;
    }

    public function setStatutFinal(string $statutFinal): void
    {
        $this->statutFinal = $statutFinal;
    }



}
