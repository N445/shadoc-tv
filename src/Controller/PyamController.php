<?php

namespace App\Controller;

use App\Form\PyamSearchType;
use App\Model\PyamSearch;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PyamController extends AbstractController
{
    public function __construct(
        private readonly SettingsRepository $settingsRepository,
    )
    {
    }

    #[Route('/pyam', name: 'APP_PYAM')]
    public function index(Request $request): Response
    {
        $settings = $this->settingsRepository->getSettings();
        if (!$pyamStartAt = $settings->getPyamStartAt()) {
//            $this->addFlash('info', 'Date pyam non renseigné');
            return $this->redirectToRoute('APP_HOMEPAGE');
        }

        $pyamSearch     = new PyamSearch();
        $pyamSearchForm = $this->createForm(PyamSearchType::class, $pyamSearch);
        $pyamSearchForm->handleRequest($request);

        if ($pyamSearchForm->isSubmitted() && $pyamSearchForm->isValid()) {
            $pyamStartAt = $pyamSearch->getDate();
        }

        $data = $this->getData($pyamStartAt, $pyamSearch->getDate());

        return $this->render('pyam/index.html.twig', [
            'form' => $pyamSearchForm->createView(),
            'data' => $data,
        ]);
    }

    private function getData(\DateTime $debutCycle, \DateTime $jourRecherche)
    {
        $cycleDuree = (2 * 60 + 60 + 5) * 60 * 1000; // 3h05 en millisecondes
        $debutVert  = 2 * 60 * 60 * 1000;            // 2h après le début du cycle
        $dureeVert  = 60 * 60 * 1000;                // 1h de vert

        $jourRecherche->setTime(0, 0, 0, 0);
        dump($jourRecherche);

        $horaires = [];

        $cycleStart                = clone $debutCycle;
        $jourRechercheTimestamp    = $jourRecherche->getTimestamp() * 1000;
        $jourRechercheFinTimestamp = $jourRechercheTimestamp + 24 * 60 * 60 * 1000;

        while ($cycleStart->getTimestamp() * 1000 < $jourRechercheFinTimestamp) {
            $vertStartTimestamp = $cycleStart->getTimestamp() * 1000 + $debutVert;
            $vertStart          = new \DateTime();
            $vertStart->setTimestamp($vertStartTimestamp / 1000);

            $vertEndTimestamp = $vertStartTimestamp + $dureeVert;
            $vertEnd          = new \DateTime();
            $vertEnd->setTimestamp($vertEndTimestamp / 1000);

            if ($vertStartTimestamp >= $jourRechercheTimestamp && $vertStartTimestamp < $jourRechercheFinTimestamp) {
                $horaires[] = [
                    'from' => $vertStart,
                    'to'   => $vertEnd,
                ];
            }

            // Avancer au prochain cycle
            $newCycleTimestamp = $cycleStart->getTimestamp() * 1000 + $cycleDuree;
            $cycleStart        = new \DateTime();
            $cycleStart->setTimestamp($newCycleTimestamp / 1000);
        }

        return $horaires;
    }
}
