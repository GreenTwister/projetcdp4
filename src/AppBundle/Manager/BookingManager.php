<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 25/10/2018
 * Time: 12:11
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Service\PaymentInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingManager
{
    const SESSION_BOOKING_KEY = "pouigpiyvoucy";
    /**
     * @var SessionInterface
     */
    private $session;
    private $em1;
    /**
     * @var PaymentInterface
     */
    private $payment;
    /**
     * @var MailManager
     */
    private $mailManager;
    /**
     * @var PriceCalculator
     */
    private $priceCalculator;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $em1,
        PaymentInterface $payment,
        PriceCalculator $priceCalculator,
        MailManager $mailManager,
        ValidatorInterface $validator
    )
    {
        $this->session = $session;
        $this->em1 = $em1;
        $this->payment = $payment;
        $this->mailManager = $mailManager;
        $this->priceCalculator = $priceCalculator;
        $this->validator = $validator;
    }


    public function initializeBooking()
    {
        $booking = new Booking();
        $this->session->set(BookingManager::SESSION_BOOKING_KEY, $booking);
        return $booking;
    }

    public function generateTickets(Booking $booking)
    {
        for ($i = 0; $i < $booking->getNbrTicket(); $i++) {
            $booking->addTicket(new Ticket());
        }

        return $booking;
    }

    public function getCurrentBooking($step = null)
    {
        $booking = $this->session->get(self::SESSION_BOOKING_KEY);
        if (!$booking instanceof Booking) {
            throw new NotFoundHttpException("Pas de commande en cours");
        }

        if ($step && count($this->validator->validate($booking,null,[$step]))) {
            throw new NotFoundHttpException("La commande courrante n'est pas valide");
        }

        return $booking;
    }

    public function payment(Booking $booking)
    {
        if ($this->payment->doPayment($booking->getTotal(), "Réservation pour telle date")) {
            $booking->setNumBooking(strtoupper(uniqid()));
            $this->mailManager->prepareMail($booking);
            $this->flushBooking($booking);
            return true;
        }

        return false;
    }

    public function flushBooking($booking)
    {
        $this->em1->persist($booking);
        $this->em1->flush($booking);
    }

    public function computePrice($booking)
    {
        // Calcule le prix de chaque billet en fonction de l'age
        $this->priceCalculator->setPricesTicketsInBooking($booking);

        // Calcule le prix total de la commande
        $cumulPrice = $this->priceCalculator->getTotalPriceForBooking($booking);
        $booking->setTotal($cumulPrice);
    }

}