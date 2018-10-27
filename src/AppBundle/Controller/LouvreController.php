<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Form\BookingFillTicketsType;
use AppBundle\Form\BookingType;
use AppBundle\Form\TicketType;
use AppBundle\Manager\BookingManager;
use AppBundle\Manager\PriceCalculator;
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
    public function indexAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->initializeBooking();
        $form = $this->createForm(BookingType::class,$booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingManager->generateTickets($booking);
            return $this->redirectToRoute('tickets');
        }
        return $this->render('Louvre/index.html.twig', array(
            'formBook' => $form->createView(),
        ));
    }

    /**
     * @Route("/tickets", name="tickets")
     */
    public function ticketsAction(Request $request, BookingManager $bookingManager, SessionInterface $session)
    {
        $booking = $bookingManager->getCurrentBooking();

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
    public function recapAction(BookingManager $bookingManager,SessionInterface $session, PriceCalculator $checkoutManager, Request $request, \Swift_Mailer $mailer)
    {
        $booking = $bookingManager->getCurrentBooking();

        // Calcule le prix de chaque billet en fonction de l'age
        $checkoutManager->setPricesTicketsInBooking($booking);

        // Calcule le prix total de la commande
        $cumulPrice = $checkoutManager->getTotalPriceForBooking($booking);
        $booking->setTotal($cumulPrice);

        if ($request->isMethod('POST')){

            // Génère un code unique
            $booking->setNumBooking(strtoupper(uniqid()));

            // Débite la carte du client
            $token = $request->request->get('stripeToken');
           \Stripe\Stripe::setApiKey($this->getParameter("stripe_secret_key"));

           \Stripe\Charge::create(array(
               "amount" => $cumulPrice * 100,
               "currency" => "eur",
               "source" => $token,
               "description" => "Paiement de test"
            ));

            // Enregistrement en bdd
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            // Envoi du mail ?!
            $booking = $session->get('booking');
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom($this->getParameter('mail_service_client'))
                ->setTo($booking->getEmail());
            //$cid = $message->embed(....);

            $message
                ->setBody(
                    $this->renderView('Email/registration.html.twig', array(
                        'booking' => $booking,
                        // 'cid' => $cid,
                        'total' => $booking->getTotal())
                    ),
                    'text/html'
                );
            $mailer->send($message);
            return $this->render('Louvre/final.html.twig', array(
                'booking' => $booking
            ));
        }

        return $this->render('Louvre/recap.html.twig', array(
            'booking' => $booking,
            'total' => $cumulPrice
        ));
    }

}