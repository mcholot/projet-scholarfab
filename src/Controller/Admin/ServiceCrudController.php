<?php

namespace App\Controller\Admin;

use DateTimeInterface;
use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('company', 'Société')->formatValue(function ($value, $entity) {
            return $entity->getCompany()->getName();
        });

        yield TextField::new('name', 'Nom');

        yield TextField::new('price', 'Prix');

        yield TimeField::new('time', 'Temps');

        yield TextField::new('category', 'Catégorie');
    }
}
