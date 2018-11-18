<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 18/11/2018
 * Time: 12:11
 */

namespace Tests\AppBundle\Validator\Manager;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Manager\PriceCalculator;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{

    public function testGetPriceForAge()
    {
        $price = new PriceCalculator(0,8,16,12,10);
        $this->assertSame(16, $price->getPriceForAge(50));
    }

    public function testGetTotalPriceForBooking()
    {
        $price = new PriceCalculator(0,8,16,12,10);
        $booking = new Booking();
        $booking->setNbrTicket(2);

        $ticket = new Ticket();
        $ticket->setPrice(16);

        $ticket2 = new Ticket();
        $ticket2->setPrice(12);

        $booking->addTicket($ticket);
        $booking->addTicket($ticket2);

        $this->assertSame(28, $price->getTotalPriceForBooking($booking));
    }

}