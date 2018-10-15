<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Booking;


class CheckManager
{
    private $tarifEnfant0_4;
    private $tarifEnfant4_12;
    private $tarifNormal;
    private $tarifSenior;
    private $tarifReduit;

    public function __construct($tarifEnfant0_4, $tarifEnfant4_12, $tarifNormal, $tarifSenior, $tarifReduit)
    {
        $this->tarifEnfant0_4 = $tarifEnfant0_4;
        $this->tarifEnfant4_12 = $tarifEnfant4_12;
        $this->tarifNormal = $tarifNormal;
        $this->tarifSenior = $tarifSenior;
        $this->tarifReduit = $tarifReduit;
    }

    public function setPricesTicketsInBooking(Booking $booking)
    {
        foreach($booking->getTickets() as $ticket)
        {
            if($ticket->getTarifRed())
            {
                $ticket->setPrice($this->tarifReduit);
            } else {
                $dateVisit = $booking->getDateVisit();
                $ageClient = $dateVisit->diff($ticket->getBirthDate())->y;
                $price = $this->getPriceForAge($ageClient);
                $ticket->setPrice($price);
            }
        }
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
            return $this->tarifEnfant4_12;
        } else {
            return $this->tarifEnfant0_4;
        }
    }
}
