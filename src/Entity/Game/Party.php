<?php

namespace App\Entity\Game;

use App\Repository\PartyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartyRepository::class)]
class Party
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbCards = null;

    #[ORM\Column]
    private ?int $nbMoves = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $player = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTime $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTime $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getNbCards(): ?int
    {
        return $this->nbCards;
    }

    public function setNbCards(?int $nbCards): static
    {
        $this->nbCards = $nbCards;

        return $this;
    }

    public function getNbMoves(): ?int
    {
        return $this->nbMoves;
    }

    public function setNbMoves(int $nbMoves): static
    {
        $this->nbMoves = $nbMoves;

        return $this;
    }

    public function addNbMove(): static
    {
        $this->nbMoves++;

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function setPlayer(?string $player): static
    {
        $this->player = $player;

        return $this;
    }
}
