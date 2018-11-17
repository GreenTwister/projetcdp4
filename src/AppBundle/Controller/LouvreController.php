<?php

namespace AppBundle\Controller;

use AppBundle\Form\BookingFillTicketsType;
use AppBundle\Form\BookingType;
use AppBundle\Manager\BookingManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class LouvreController extends Controller
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->initializeBooking();
        $form = $this->createForm(BookingType::class, $booking);
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
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function ticketsAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->getCurrentBooking("step1");

        $form = $this->createForm(BookingFillTicketsType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingManager->computePrice($booking);
            return $this->redirectToRoute('recap');
        }
        return $this->render('Louvre/tickets.html.twig', array(
            'formTicket' => $form->createView(),
            'booking' => $booking
        ));
    }

    /**
     * @Route("/recap", name="recap")
     * @param BookingManager $bookingManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recapAction(BookingManager $bookingManager,Request $request)
    {
        $booking = $bookingManager->getCurrentBooking("step2");
        if ($request->isMethod('POST')) {
            if ($bookingManager->payment($booking)) {
                return $this->render('Louvre/final.html.twig', array(
                    'booking' => $booking
                ));
            }else{
                $this->addFlash('notice', ' Erreur survenue pendant le paiement , veuillez réessayer ultérieurement');
                return $this->render('Louvre/recap.html.twig', array(
                    'booking' => $booking
                ));


            }
        }
        return $this->render('Louvre/recap.html.twig', array(
            'booking' => $booking
        ));
    }

    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentionAction()
    {
        return $this->render('Louvre/mentions.html.twig');
    }

}