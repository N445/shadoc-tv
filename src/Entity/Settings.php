<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $pyamStartAt = null;


    #[ORM\Column(nullable: true)]
    private ?int $gameNbCard = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPyamStartAt(): ?\DateTime
    {
        return $this->pyamStartAt;
    }

    public function setPyamStartAt(?\DateTime $pyamStartAt): static
    {
        $this->pyamStartAt = $pyamStartAt;

        return $this;
    }

    public function getGameNbCard(): ?int
    {
        return $this->gameNbCard;
    }

    public function setGameNbCard(?int $gameNbCard): void
    {
        $this->gameNbCard = $gameNbCard;
    }
}
