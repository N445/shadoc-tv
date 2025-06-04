<?php

namespace App\Controller;

use App\Form\PyamSearchType;
use App\Model\PyamSearch;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game')]
final class GameController extends AbstractController
{
    public function __construct(
        private readonly SettingsRepository $settingsRepository,
    )
    {
    }

    #[Route('/', name: 'APP_GAME')]
    public function index(Request $request): Response
    {
        return $this->redirectToRoute('APP_HOMEPAGE');
    }

    #[Route('/card', name: 'APP_GAME_CARD')]
    public function card(): Response
    {
        $settings = $this->settingsRepository->getSettings();
        if (!$nbCards = $settings->getGameNbCard()) {
            $this->addFlash('warning','Il faut définir un nombre de cartes pour jouer');
            return $this->redirectToRoute('APP_HOMEPAGE');
        }
        return $this->render('game/card.html.twig', [
            'nbCards'=>$nbCards
        ]);
    }
}
