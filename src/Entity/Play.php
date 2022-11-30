<?php

namespace App\Entity;

use App\Repository\PlayRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayRepository::class)]
class Play
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ScoreUser = null;

    #[ORM\ManyToOne(inversedBy: 'playedquizz')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'played')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quizz $quizz = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScoreUser(): ?int
    {
        return $this->ScoreUser;
    }

    public function setScoreUser(int $ScoreUser): self
    {
        $this->ScoreUser = $ScoreUser;

        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getQuizz(): ?Quizz
    {
        return $this->quizz;
    }

    public function setQuizz(?Quizz $quizz): self
    {
        $this->quizz = $quizz;

        return $this;
    }
}
