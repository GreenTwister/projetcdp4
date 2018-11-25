<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 18/11/2018
 * Time: 14:34
 */

namespace Tests\AppBundle\Validator\Manager;


use AppBundle\Entity\Booking;
use AppBundle\Manager\BookingManager;
use AppBundle\Manager\MailManager;
use AppBundle\Manager\PriceCalculator;
use AppBundle\Service\PaymentInterface;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingManagerTest extends TestCase
{
    /**
     * @var BookingManager
     */
    private $bookingManager;
    private $priceCalculator;

    public function testGenerateTickets()
    {
        $booking = new Booking();
        $booking->setNbrTicket(3);
        $this->bookingManager->generateTickets($booking);
        $this->assertSame(3, count($booking->getTickets()));
    }

    public function setUp()
    {
//        $this->bookingManager = $this
//            ->getMockBuilder('AppBundle\Manager\BookingManager')
//            ->disableOriginalConstructor()
//            ->setMethods(null)
//            ->getMock();

        $this->session = new Session(new MockArraySessionStorage());
        $this->em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $this->payment = $this->getMockBuilder(PaymentInterface::class)->disableOriginalConstructor()->getMock();
        $this->priceCalculator = $this->getMockBuilder(PriceCalculator::class)->disableOriginalConstructor()->getMock();
        $this->mailManager = $this->getMockBuilder(MailManager::class)->disableOriginalConstructor()->getMock();
        $this->validator = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();
        $this->bookingManager = new BookingManager($this->session,$this->em,$this->payment,$this->priceCalculator,$this->mailManager,$this->validator);

    }

    public function testGetCurrentBookingOk(){
        $this->session->set(BookingManager::SESSION_BOOKING_KEY,new Booking());
        $this->bookingManager->getCurrentBooking();
    }

    public function testGetCurrentBookingWithValidationOk(){
        $this->session->set(BookingManager::SESSION_BOOKING_KEY,new Booking());
        $this->validator->method('validate')->willReturn([]);
        $this->bookingManager->getCurrentBooking('step1');
    }

    public function testGetCurrentBookingWithValidationKo(){
        $this->session->set(BookingManager::SESSION_BOOKING_KEY,new Booking());
        $this->expectException(NotFoundHttpException::class);
        $this->validator->method('validate')->willReturn(['error1','error2']);
        $this->bookingManager->getCurrentBooking('step1');
    }

    public function testGetCurrentBookingKo(){
        $this->expectException(NotFoundHttpException::class);
        $this->bookingManager->getCurrentBooking();
    }

}