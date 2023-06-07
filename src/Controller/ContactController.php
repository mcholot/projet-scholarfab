<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $contact = $form->getData();

            // Remplir les champs de l'entité Contact avec les données du formulaire
            $contact->setLu(false);

            // Enregistrez l'entité dans la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une autre page après le traitement du formulaire.
            return $this->redirectToRoute('home');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/messages', name: 'messages')]
    #[IsGranted('ROLE_ADMIN')]
    public function view(Security $security, Request $request, ContactRepository $contactRepo): Response
    {
        // Si l'utilisateur n'est pas connecté
        // Rediriger vers le formulaire de connexion
        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login');
        }
        try{
            $contacts = $contactRepo->findAll();


            return $this->render('contact/messages_list.html.twig', [
                'contacts' => $contacts,
            ]);
        }
        catch(\Exception $ex) {
            throw $ex;
        }
    }

    #[Route('/admin/messages/{id}/update', name: 'update_message')]
    #[IsGranted('ROLE_ADMIN')]
    public function updateMessageStatus(Contact $contact, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si la requête est de type PUT
        if ($request->isMethod('PUT')) {
            // Récupérer la valeur de la case à cocher
            $lu = $request->request->get('lu');
            // Mettre à jour la propriété "lu" de l'entité Contact
            $contact->setLu($lu);
            // Enregistrer les modifications en base de données
            $entityManager->flush();
        }

        // Rediriger l'utilisateur vers la page des messages
        return $this->redirectToRoute('messages');
    }

    #[Route('/admin/message/{id}/delete', name: 'delete_message')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Contact $contact, Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si l'utilisateur n'est pas connecté
        // Rediriger vers le formulaire de connexion
        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_message', ['id' => $contact->getId()]))
            ->setMethod('DELETE')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Supprimer la réservation en base de données
            $entityManager->remove($contact);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page d'accueil
            return $this->redirectToRoute('messages');
        }

        // Gérer l'annulation de la réservation
        return $this->render('contact/delete_message.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact
        ]);
    }
}
