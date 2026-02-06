<?php

namespace App\Entity;

use App\Repository\WorkspaceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkspaceRepository::class)]
class Workspace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'workspaces')]
    #[ORM\JoinColumn(nullable: false)]
    private Candidat $candidat;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

     #[ORM\OneToMany(mappedBy: 'workspace', targetEntity: Track::class)]
    private Collection $tracks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
