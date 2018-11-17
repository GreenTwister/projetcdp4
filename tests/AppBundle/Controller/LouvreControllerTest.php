<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 10/11/2018
 * Time: 16:15
 */

namespace Tests\AppBundle\Controller;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Manager\BookingManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvreControllerTest extends WebTestCase
{

    public function testHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testTicketsKO()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tickets');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testFormBooking()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Suivant')->form(array(
            'sabate_louvrebundle_booking[dateVisit]' => '02/01/2019',
            'sabate_louvrebundle_booking[nbrTicket]' => 1,
            'sabate_louvrebundle_booking[email]' => 'blabla.blabla.com', // mail invalide
            'sabate_louvrebundle_booking[typeTicket]' => 'Demi-journée',
        ));
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('.form-error-message')->count() >= 1);
    }

    public function testFormTicket()
    {
        $client = static::createClient();
        $booking = new Booking();
        $booking->setNbrTicket(1);
        $booking->setEmail("toto@toto.fr");
        $booking->setDateVisit(new \DateTime('2018-11-12'));
        $booking->addTicket(new Ticket());
        $client->getContainer()->get('session')->set(BookingManager::SESSION_BOOKING_KEY, $booking);
        $crawler = $client->request('GET', '/tickets');
        $form = $crawler->selectButton('Commander')->form(array(
            'sabate_louvrebundle_booking[tickets][0][name]' => 'Boucher',
            'sabate_louvrebundle_booking[tickets][0][surname]' => 'Jean',
            'sabate_louvrebundle_booking[tickets][0][birthDate][day]' => 20,
            'sabate_louvrebundle_booking[tickets][0][birthDate][month]' => 10,
            'sabate_louvrebundle_booking[tickets][0][birthDate][year]' => 1992,
            'sabate_louvrebundle_booking[tickets][0][nationality]' => 'France',
            'sabate_louvrebundle_booking[tickets][0][tarifRed]' => 1
        ));

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSame(1,$crawler->filter('h1')->count());
    }
}