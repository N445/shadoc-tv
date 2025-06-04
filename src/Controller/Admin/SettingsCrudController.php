<?php

namespace App\Controller\Admin;

use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class SettingsCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly SettingsRepository     $settingsRepository,
        private readonly EntityManagerInterface $em,
        private readonly AdminUrlGenerator      $adminUrlGenerator,
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Settings::class;
    }

    public function index(AdminContext $context)
    {
        $settingsUrl = $this->adminUrlGenerator
            ->setController(SettingsCrudController::class)
            ->setAction('edit')
            ->setEntityId($this->getSettings()->getId())
            ->generateUrl()
        ;

        return $this->redirect($settingsUrl);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('pyamStartAt'),
            NumberField::new('gameNbCard'),
        ];
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
