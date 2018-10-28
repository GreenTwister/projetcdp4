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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingManager
{
    const SESSION_BOOKING_KEY = "pouigpiyvoucy";
    /**
     * @var SessionInterface
     */
    private $session;
    private $secretKey;

    public function __construct(SessionInterface $session, $secretKey)
    {
        $this->session = $session;
        $this->secretKey = $secretKey;
    }


    public function initializeBooking()
    {
        $booking = new Booking();
        $this->session->set(BookingManager::SESSION_BOOKING_KEY, $booking);
        return $booking;
    }

    public function generateTickets(Booking $booking)
    {
        for ($i = 0; $i < $booking->getNbrTicket(); $i++){
            $booking->addTicket(new Ticket());
        }

        return $booking;
    }

    public function getCurrentBooking()
    {
        $booking = $this->session->get(self::SESSION_BOOKING_KEY);

        if(!$booking instanceof Booking){
            throw new NotFoundHttpException("Pas de commande en cours");
        }

        return $booking;
    }

    public function Payment($token, $total)
    {
        \Stripe\Stripe::setApiKey($this->secretKey);

        \Stripe\Charge::create(array(
            "amount" => $total * 100,
            "currency" => "eur",
            "source" => $token,
            "description" => "Paiement de test"
        ));

    }

}