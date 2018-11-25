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
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testTicketsKO()
    {
        $client = static::createClient();
        $client->request('GET', '/tickets');
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

}