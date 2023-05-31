<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // Récupérer l'erreur de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupérer le dernier nom d'utilisateur saisi
        // $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            // 'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/user/{name}', name: 'user')]
    public function index(?User $user, Security $security): Response
    {
        // Si l'utilisateur n'est pas connecté
        // Rediriger vers le formulaire de connexion
        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login');
        }

        return $this->render('security/index.html.twig', [
            'user' => $user
        ]);
    }
}
