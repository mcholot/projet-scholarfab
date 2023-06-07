<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Employee;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\EmployeeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    )
    {
    }

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('login');
        }

        $url = $this->adminUrlGenerator->setController(EmployeeCrudController::class)
            ->generateUrl();

        return new RedirectResponse($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Faudra Tif Hair - Panel d\'administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Aller sur le site', 'fa fa-undo', 'home');

        yield MenuItem::subMenu('Gestion des coiffeurs', 'fas fa-scissors')->setSubItems([
            MenuItem::linkToCrud('Tous les coiffeurs', 'fas fa-scissors', Employee::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Employee::class)->setAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::subMenu('Gestion des réservations', 'fas fa-calendar-days')->setSubItems([
            MenuItem::linkToCrud('Tous les réservations', 'fas fa-calendar-days', Reservation::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Reservation::class)->setAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::subMenu('Gestion des services', 'fas fa-screwdriver-wrench')->setSubItems([
            MenuItem::linkToCrud('Tous les services', 'fas fa-screwdriver-wrench', Service::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Service::class)->setAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::subMenu('Gestion des utilisateurs', 'fas fa-users')->setSubItems([
            MenuItem::linkToCrud('Tous les utilisateurs', 'fas fa-users', User::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-user-plus', User::class)->setAction(Crud::PAGE_NEW)
        ]);
    }
}
