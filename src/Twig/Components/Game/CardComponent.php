<?php

namespace App\Twig\Components\Game;

use App\Entity\Game\Party;
use App\Repository\Game\PlayerRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class CardComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $nbCards;

    #[LiveProp]
    public ?Party $party = null;

    public function __construct(
        private readonly PlayerRepository $playerRepository
    )
    {
    }

    public function getPlayers(){
        return $this->playerRepository->findAll();
    }

    #[LiveProp]
    public function startParty()
    {

    }
}
