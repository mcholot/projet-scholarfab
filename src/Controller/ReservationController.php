<?php

namespace App\Controller;

use DateTime;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'reservation')]
    public function index(Security $security, Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        // Si l'utilisateur n'est pas connecté
        // Rediriger vers le formulaire de connexion
        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $reservation = $form->getData();

            $newReservation = new Reservation();

            // Remplir les champs de l'entité Reservation avec les données du formulaire
            $newReservation->setDate($reservation->getDate());
            $newReservation->setHour($reservation->getHour());
            $newReservation->setEmployee($reservation->getEmployee());
            $newReservation->setServices($reservation->getServices());
            $newReservation->setUser($this->getUser());

            // Enregistrer la réservation dans la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->persist($newReservation);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une autre page après le traitement du formulaire.
            return $this->redirectToRoute('home');
        }

        return $this->render('reservation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
