<?php

namespace App\Twig\Components\Game;

use App\Entity\Game\Party;
use App\Entity\Game\Player;
use App\Repository\Game\CardRepository;
use App\Repository\Game\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
final class CardComponent
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    /**
     * @var Player[]
     */
    #[LiveProp]
    public array $players = [];

    #[LiveProp]
    public int $nbCards;

    #[LiveProp(useSerializerForHydration: true, serializationContext: [
        'groups' => ['game:card'],
    ])]
    public ?Party $party = null;

    public function __construct(
        private readonly PlayerRepository       $playerRepository,
        private readonly EntityManagerInterface $em,
        private readonly CardRepository         $cardRepository,
    )
    {
    }

    #[PostMount]
    public function postMount(): void
    {
        $this->players = $this->playerRepository->findAll();
    }

    #[LiveAction]
    public function startParty(#[LiveArg] $playerName): void
    {
        if (!$player = $this->getPlayer($playerName)) {
            $player = (new Player())->setUsername($playerName);
            $this->em->persist($player);
            $this->em->flush();
            $this->players = $this->playerRepository->findAll();
        }
        $this->party = (new Party())
            ->setPlayer($player)
            ->setNbCards($this->nbCards)
            ->setStartAt(new \DateTime())
            ->setNbMoves(0)
        ;

        $cards = $this->cardRepository->getNbCards($this->nbCards / 2);
        $cards = array_merge($cards, $cards);
        shuffle($cards);
        $this->party->setCards($cards);
        $this->dispatchBrowserEvent('game:card:start');
    }

    #[LiveAction]
    public function move(#[LiveArg] int $cardAId, #[LiveArg] int $cardBId): void
    {
        if (!$this->party) {
            return;
        }

        [$cardA, $cardB] = $this->cardRepository->getCardsByIds([$cardAId, $cardBId]);

        dump($cardA, $cardB);

        $this->party->addNbMove();
        $this->em->persist($this->party);
        $this->em->flush();
        $this->dispatchBrowserEvent('game:card:move', [
            'cardA' => $cardA,
            'cardB' => $cardB,
        ]);
    }

    private function getPlayer(string $playerName): ?Player
    {
        return array_filter(
            $this->players,
            fn(Player $player) => $player->getUsername() === $playerName,
        )[0] ?? null;
    }
}
