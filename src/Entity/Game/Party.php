<?php

namespace App\Entity\Game;

use App\Repository\Game\PartyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PartyRepository::class)]
class Party
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['game:card'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['game:card'])]
    private ?\DateTime $startAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['game:card'])]
    private ?\DateTime $endAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['game:card'])]
    private ?int $nbCards = null;

    #[ORM\Column]
    #[Groups(['game:card'])]
    private ?int $nbMoves = 0;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'parties')]
    #[Groups(['game:card'])]
    private ?Player $player = null;

    /**
     * @var Card[]|null
     */
    #[Groups(['game:card'])]
    private ?array $cards = null;

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

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getCards(): ?array
    {
        return $this->cards;
    }

    public function setCards(?array $cards): void
    {
        $this->cards = $cards;
    }
}
