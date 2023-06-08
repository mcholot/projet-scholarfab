<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

class ServicesCardController extends AbstractController
{
    private $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    #[Route('/services/card', name: 'services_card')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // Récupérer tous les services dans la base de données
        $services = $this->serviceRepository->findAll();

        // Regrouper les services par catégorie
        $groupedServices = [];
        foreach ($services as $service) {
            $category = $service->getCategory();
            $groupedServices[$category][] = $service;
        }

        // Retourner vers la vue des services avec tous les éléments de l'entité service
        return $this->render('services_card/index.html.twig', [
            'groupedServices' => $groupedServices,
        ]);
    }
}
