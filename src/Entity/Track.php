<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    private Workspace $workspace;

    #[ORM\Column]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private string $category;

    #[ORM\Column]
    private \DateTimeImmutable $startDate;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column]
    private string $status;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private string $visibility;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getVisibility(): string
    {
        return $this->visibility;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setVisibility(string $visibility): void
    {
        $this->visibility = $visibility;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

}
