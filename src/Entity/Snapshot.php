<?php

namespace App\Entity;

use App\Repository\SnapshotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SnapshotRepository::class)]
class Snapshot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private Track $track;

    #[ORM\ManyToOne]
    private User $author;

    #[ORM\Column]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $message;

    #[ORM\Column]
    private bool $isFinal = false;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function getTrack(): Track
    {
        return $this->track;
    }

    public function setTrack(Track $track): void
    {
        $this->track = $track;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal): void
    {
        $this->isFinal = $isFinal;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

}
