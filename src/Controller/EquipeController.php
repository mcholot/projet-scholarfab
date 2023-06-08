<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipeController extends AbstractController
{
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route('/staff', name: 'equipe')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // Récupérer tous les membres de l'équipe dans la base de données
        $equipes = $this->employeeRepository->findAll();

        // Retourner vers la vue équipe avec tous les membres de l'entité équipe
        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes,
        ]);
    }
}
