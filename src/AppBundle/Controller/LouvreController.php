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
        /** @var Booking $booking */
        $booking = $session->get('booking');

        if ($booking == null){
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
    public function recapAction(SessionInterface $session, CheckManager $checkoutManager, Request $request,\Swift_Mailer $mailer)
    {
        $booking = $session->get('booking');

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
           \Stripe\Stripe::setApiKey("sk_test_nX9TGnKYTMCx2ot3A2H2ioeJ");

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
                ->setFrom('contactlouvre84@gmail.com')
                ->setTo($booking->getEmail())
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Email/registration.html.twig
                        'Email/registration.html.twig',
                        array('booking' => $booking)
                    ),
                    'text/html'
                );
            $mailer->send($message);
            return $this->render('Louvre/final.html.twig');
        }

        return $this->render('Louvre/recap.html.twig', array(
            'booking' => $booking,
            'total' => $cumulPrice
        ));
    }

}