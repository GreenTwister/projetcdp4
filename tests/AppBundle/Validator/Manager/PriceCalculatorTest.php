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
    /**
     * @var PriceCalculator
     */
    private $priceCalculator;

    /**
     * @dataProvider agesPricesProvider
     * @param $age
     * @param $expectedPrice
     */
    public function testGetPriceForAge($age,$expectedPrice)
    {
        $this->assertSame(16, $this->priceCalculator->getPriceForAge(50));
    }

    public function agesPricesProvider()
    {
        return [
            [50,16],
            [3,0],
        ];
    }

    protected function setUp()
    {
        $this->priceCalculator = new PriceCalculator(0,8,16,12,10);
    }


    public function testGetTotalPriceForBooking()
    {
        $booking = new Booking();
        $booking->setNbrTicket(2);

        $ticket = new Ticket();
        $ticket->setPrice(16);

        $ticket2 = new Ticket();
        $ticket2->setPrice(12);

        $booking->addTicket($ticket);
        $booking->addTicket($ticket2);

        $this->assertSame(28, $this->priceCalculator->getTotalPriceForBooking($booking));
    }

}