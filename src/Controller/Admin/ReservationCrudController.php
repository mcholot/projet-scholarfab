<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('employee', 'Coiffeur')->formatValue(function ($value, $entity) {
            return $entity->getEmployee()->getLastName() . ' ' . $entity->getEmployee()->getFirstName();
        });

        yield AssociationField::new('user', 'Utilisateur')->formatValue(function ($value, $entity) {
            return $entity->getUser()->getName() . ' ' . $entity->getUser()->getFirstName();
        });

        yield DateField::new('date');

        yield TextField::new('hour');

        yield AssociationField::new('services', 'Service')->formatValue(function ($value, $entity) {
            return $entity->getServices()->getName();
        });
    }
}
