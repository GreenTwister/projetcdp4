<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Booking;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CheckManager
{
    private $session;
    private $tarifGratuit;
    private $tarifEnfant;
    private $tarifNormal;
    private $tarifSenior;
    private $tarifReduit;

    public function __construct(SessionInterface $session, $tarifGratuit, $tarifEnfant, $tarifNormal, $tarifSenior, $tarifReduit)
    {
        $this->session = $session;
        $this->tarifGratuit = $tarifGratuit;
        $this->tarifEnfant = $tarifEnfant;
        $this->tarifNormal = $tarifNormal;
        $this->tarifSenior = $tarifSenior;
        $this->tarifReduit = $tarifReduit;
    }

    public function setPricesTicketsInBooking(Booking $booking)
    {
        $cumulPrice = 0;
        foreach($booking->getTickets() as $ticket)
        {
            if($ticket->getTarifRed())
            {
                $price = $this->tarifReduit;
            } else {
                $price = $this->getPriceForAge($ticket->getAge());
            }

            $cumulPrice += $price;
            $ticket->setPrice($price);
        }
        $booking->setTotal($cumulPrice);

    }

    public function getTotalPriceForBooking(Booking $booking)
    {
        $cumulPrice = 0;
        foreach($booking->getTickets() as $ticket)
        {
            $cumulPrice += $ticket->getPrice();
        }
        return $cumulPrice;
    }

    public function getPriceForAge($age)
    {
        if($age > 60) {
            return $this->tarifSenior;
        } elseif($age > 12) {
            return $this->tarifNormal;
        } elseif ($age > 4) {
            return $this->tarifEnfant;
        } else {
            return $this->tarifGratuit;
        }
    }

    public function checkBookingValid($booking)
    {

        return true;
    }
}
