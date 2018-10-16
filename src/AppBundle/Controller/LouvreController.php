<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Form\BookingFillTicketsType;
use AppBundle\Form\BookingType;
use AppBundle\Form\TicketType;
use AppBundle\Manager\CheckManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class LouvreController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $booking = new Booking();
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

        if ($booking == null){
            return $this->redirectToRoute('home');
        }

        // Vérifie si la date n'est pas mardi ou dimanche
        $manager = $this->get('check.manager');
        $bookingValid = $manager->checkBookingValid($booking);
        if (!$bookingValid) {
            return $this->redirectToRoute('home');
        }

        if(!$request->isMethod('POST')){

          for ($i = 0; $i < $booking->getNbrTicket(); $i++){
               $booking->addTicket(new Ticket());
           }
        }

        $form = $this->createForm(BookingFillTicketsType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $session->set('booking', $booking);
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

        // Calcule le prix de chaque billet en fonction de l'age
        $checkoutManager = $this->get('check.manager');
        $checkoutManager->setPricesTicketsInBooking($booking);

        // Calcule le prix total de la commande
        $cumulPrice = $checkoutManager->getTotalPriceForBooking($booking);
        $booking->setTotal($cumulPrice);

        return $this->render('Louvre/recap.html.twig', array(
            'booking' => $booking,
            'total' => $cumulPrice
        ));
    }
}