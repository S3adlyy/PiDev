<?php

namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntretienRepository::class)]
class Entretien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private Postulation $postulation;

    #[ORM\Column]
    private \DateTimeImmutable $dateEntretien;

    #[ORM\Column]
    private string $type;

    #[ORM\Column]
    private string $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getPostulation(): Postulation
    {
        return $this->postulation;
    }

    public function setPostulation(Postulation $postulation): void
    {
        $this->postulation = $postulation;
    }

    public function getDateEntretien(): \DateTimeImmutable
    {
        return $this->dateEntretien;
    }

    public function setDateEntretien(\DateTimeImmutable $dateEntretien): void
    {
        $this->dateEntretien = $dateEntretien;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


}
