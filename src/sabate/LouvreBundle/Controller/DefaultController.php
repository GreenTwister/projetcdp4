<?php

namespace sabate\LouvreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use sabate\LouvreBundle\Entity\Ticket;
use sabate\LouvreBundle\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use sabate\LouvreBundle\Entity\Booking;
use sabate\LouvreBundle\Form\BookingType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $booking = new Booking();
        $form   = $this->createForm(BookingType::class, $booking);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            return $this->redirectToRoute('tickets');
        }

        return $this->render('@sabateLouvre/Louvre/index.html.twig', array(
            'formBook' => $form->createView(),
        ));
    }

    /**
     * @Route("/tickets", name="tickets")
     */
    public function ticketsAction(Request $request)
    {
        $ticket = new Ticket();
        $form   = $this->createForm(TicketType::class, $ticket);
        return $this->render('@sabateLouvre/Louvre/tickets.html.twig', array(
            'formTicket' => $form->createView(),
        ));
    }

    /**
     * @Route("/recap", name="recap")
     */
    public function recapAction()
    {
        return $this->render('@sabateLouvre/Louvre/recap.html.twig');
    }
}
