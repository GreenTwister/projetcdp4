<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Form\BookingFillTicketsType;
use AppBundle\Form\BookingType;
use AppBundle\Form\TicketType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LouvreController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(BookingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $session->set('booking', $form->getData());
            return $this->redirectToRoute('tickets');
        }
        return $this->render('Louvre/index.html.twig', array(
            'formBook' => $form->createView(),
        ));
    }

    /**
     * @Route("/tickets", name="tickets")
     */
    public function ticketsAction(Request $request, SessionInterface $session)
    {
        $booking = $session->get('booking');
        for ($i = 0; $i < $booking->getNbrTicket(); $i++){
            $booking->addTicket(new Ticket());
        }
        $form = $this->createForm(BookingFillTicketsType::class, $booking);

        if ($form->isSubmitted() && $form->isValid()) {

            $session->set('booking', $form->getData());
            return $this->redirectToRoute('recap');
        }
        return $this->render('Louvre/tickets.html.twig', array(
            'formTicket' => $form->createView(),
            'booking' => $booking
        ));
    }

    /**
     * @Route("/recap", name="recap")
     */
    public function recapAction(SessionInterface $session)
    {
        $booking = $session->get('booking');
        foreach($booking->getTickets() as $ticket){
            $ticket->setBooking($booking);
        }
        return $this->render('Louvre/recap.html.twig', array(
            'booking' => $booking
        ));
    }
}