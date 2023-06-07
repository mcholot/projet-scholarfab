<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Form\EditReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MyReservationsController extends AbstractController
{
    #[Route('/{name}/reservations', name: 'my_reservations')]
    public function index(?User $user, Security $security, Request $request, ReservationRepository $reservationRepo): Response
    {
        // Si l'utilisateur n'est pas connecté
        // Rediriger vers le formulaire de connexion
        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login');
        }
        try{
            $reservations = $reservationRepo->createQueryBuilder('r')
                ->innerJoin('r.user', 'u')
                ->where ('u.id = :id')
                ->setParameter ('id',$this->getUser())
                ->getQuery();
            $result = $reservations->getResult();
            return $this->render('my_reservations/index.html.twig', [
                'reservations' => $result,
                'user' => $user
            ]);
        }
        catch(\Exception $ex) {
            throw $ex;
        }
    }

    #[Route('/{name}/reservation/{id}/edit', name: 'edit_reservation')]
    public function edit(?User $user, Reservation $reservation, Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si l'utilisateur n'est pas connecté
        // Rediriger vers le formulaire de connexion
        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login');
        }

        // Code pour gérer la modification de la réservation

        $form = $this->createForm(EditReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            // Redirigez l'utilisateur vers une autre page après le traitement du formulaire.
            return $this->redirectToRoute('home');
        }

        return $this->render('my_reservations/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/reservation/{id}/cancel', name: 'cancel_reservation', methods: ['GET', 'POST', 'DELETE'])]
    public function cancel(Reservation $reservation, Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si l'utilisateur n'est pas connecté
        // Rediriger vers le formulaire de connexion
        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('cancel_reservation', ['id' => $reservation->getId()]))
            ->setMethod('DELETE')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Supprimer la réservation en base de données
            $entityManager->remove($reservation);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page d'accueil
            return $this->redirectToRoute('home');
        }

        // Gérer l'annulation de la réservation
        return $this->render('my_reservations/cancel.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation
        ]);
    }
}
