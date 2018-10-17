<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Booking;


class CheckManager
{
    private $session;
    private $tarifEnfant0_4;
    private $tarifEnfant4_12;
    private $tarifNormal;
    private $tarifSenior;
    private $tarifReduit;

    public function __construct($session, $tarifEnfant0_4, $tarifEnfant4_12, $tarifNormal, $tarifSenior, $tarifReduit)
    {
        $this->session = $session;
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

    public function checkBookingValid($booking)
    {
        $date = $booking->getDateVisit();

        // Si date est un mardi ou dimanche
        $day = $date->format('D');
        if ($day == "Tue" || $day == "Sun") {
            $this->session->getFlashBag()->add('warning', 'Le musée est fermé tous les mardi et les dimanche, veuillez sélectionner un autre jour');
            return false;
        }

        // Si date passée
        $today = new \DateTime('now');
        if ($date->format('Y-m-d') < $today->format('Y-m-d')){
            $this->session->getFlashBag()->add('warning', 'Vous ne pouvez pas prendre de ticket pour une date passée');
            return false;
        }
        // Si date est un jour férié
        $closedDays = ['01-05', '01-11', '25-12'];
        $day = $date->format('d-m');
        if (in_array($day, $closedDays)) {
            $this->session->getFlashBag()->add('warning', 'Le musée est fermé à cette date, veuillez sélectionner un autre jour');
            return false;
        }
        // Si billet journée alors qu'il est plus de 14h
        $hour = $today->format('H');
        $today = $today->format('d/m/y');
        $dateBooking = $date->format('d/m/y');
        if ($today == $dateBooking && $booking->getTypeTicket() == 'Journée' && (int)$hour >= 14) {
            $this->session->getFlashBag()->add('warning', 'Les billets journée ne sont plus disponible après 14h');
            return false;
        }

        return true;
    }
}
