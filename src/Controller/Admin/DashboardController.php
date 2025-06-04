<?php

namespace App\Controller\Admin;

use App\Entity\Game\Card;
use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly SettingsRepository     $settingsRepository,
        private readonly EntityManagerInterface $em,
        private readonly AdminUrlGenerator      $adminUrlGenerator,
    )
    {
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
                        ->setTitle('App')
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Accueil', 'fa fa-home', 'APP_HOMEPAGE');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        $settingsUrl = $this->adminUrlGenerator
            ->setController(SettingsCrudController::class)
            ->setAction('edit')
            ->setEntityId($this->getSettings()->getId())
            ->generateUrl()
        ;
        yield MenuItem::linkToUrl('Paramètres', 'fa-solid fa-gears', $settingsUrl);
        yield MenuItem::linkToCrud('Cartes', 'fa-solid fa-gears', Card::class);
    }

    private function getSettings(): Settings
    {
        if ($settings = $this->settingsRepository->getSettings()) {
            return $settings;
        }
        $settings = new Settings();
        $this->em->persist($settings);
        $this->em->flush();
        return $settings;
    }
}
