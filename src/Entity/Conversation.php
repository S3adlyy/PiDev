<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private User $user1;

    #[ORM\ManyToOne]
    private User $user2;

    #[ORM\Column]
    private \DateTimeImmutable $dateCreation;

    #[ORM\Column(nullable: true)]
    private ?string $dernierMessage = null;

    #[ORM\Column]
    private string $statut;

    //#[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class)]
    //private Collection $messages;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUser1(): User
    {
        return $this->user1;
    }

    public function setUser1(User $user1): void
    {
        $this->user1 = $user1;
    }

    public function getUser2(): User
    {
        return $this->user2;
    }

    public function setUser2(User $user2): void
    {
        $this->user2 = $user2;
    }

    public function getDateCreation(): \DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getDernierMessage(): ?string
    {
        return $this->dernierMessage;
    }

    public function setDernierMessage(?string $dernierMessage): void
    {
        $this->dernierMessage = $dernierMessage;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function setMessages(Collection $messages): void
    {
        $this->messages = $messages;
    }


}
