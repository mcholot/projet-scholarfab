<?php

namespace App\Controller\Admin;

use App\Entity\Employee;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EmployeeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Employee::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('company', 'Société')->formatValue(function ($value, $entity) {
            return $entity->getCompany()->getName();
        });

        yield TextField::new('lastname', 'Nom');

        yield TextField::new('firstname', 'Prénom');

        yield TextField::new('description');
    }
}
