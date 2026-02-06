<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'feedback')]
    #[ORM\JoinColumn(nullable: false)]
    private RenduMission $rendu;

    #[ORM\Column(type: 'text')]
    private string $commentaire;

    #[ORM\Column]
    private int $note;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;
}
