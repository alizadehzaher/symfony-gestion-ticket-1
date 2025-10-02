<?php
// src/Controller/TicketController.php
namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TicketController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket, ['is_admin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ticket);
            $entityManager->flush();

            $this->addFlash('success', 'Votre ticket a été créé avec succès!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tickets', name: 'app_tickets')]
    #[IsGranted('ROLE_STAFF')]
    public function listTickets(TicketRepository $ticketRepository): Response
    {
        $tickets = $ticketRepository->findAllOrderedByDate();

        return $this->render('ticket/list.html.twig', [
            'tickets' => $tickets,
        ]);
    }

    #[Route('/ticket/{id}', name: 'app_ticket_show')]
    #[IsGranted('ROLE_STAFF')]
    public function showTicket(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/ticket/{id}/edit', name: 'app_ticket_edit')]
    #[IsGranted('ROLE_STAFF')]
    public function editTicket(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $form = $this->createForm(TicketType::class, $ticket, ['is_admin' => $isAdmin]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($ticket->getStatus() === 'Fermé' && !$ticket->getCloseDate()) {
                $ticket->setCloseDate(new \DateTime());
            } elseif ($ticket->getStatus() !== 'Fermé') {
                $ticket->setCloseDate(null);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Ticket mis à jour avec succès!');
            return $this->redirectToRoute('app_ticket_show', ['id' => $ticket->getId()]);
        }

        return $this->render('ticket/edit.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket,
        ]);
    }
}