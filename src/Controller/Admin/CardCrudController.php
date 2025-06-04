<?php

namespace App\Controller\Admin;

use App\Entity\Game\Card;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Card::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('isMain', 'Principal'),
            ImageField::new('imageName', 'Image')->setBasePath('/uploads/game/card')->hideOnForm(),
            Field::new('imageFile', 'Image')
                 ->setFormType(VichImageType::class)
                 ->setFormTypeOptions(
                     [
                         'attr' => [
                             'accept' => implode(',', [
                                 'image/png',
                                 'image/gif',
                                 'image/jpeg',
                                 'image/webp',
                             ]),
                         ],
                     ],
                 )
                 ->onlyOnForms(),
        ];
    }
}
