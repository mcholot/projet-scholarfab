<?php

namespace App\Controller\Admin;

use DateTimeInterface;
use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
        yield AssociationField::new('company', 'Société');

        yield TextField::new('name', 'Nom');

        yield TextField::new('price', 'Prix');

        yield DateTimeField::new('time', 'Temps')->formatValue(function ($value, $entity) {
            // Vérifier si la valeur est un objet DateTime
            if ($value instanceof DateTimeInterface) {
                // Formater la valeur avec le format heure-minute (H:i)
                return $value->format('H:i');
            }

            return $value;
        });

        yield TextField::new('category', 'Catégorie');
    }
}
