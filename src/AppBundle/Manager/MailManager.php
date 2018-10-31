<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 31/10/2018
 * Time: 12:24
 */

namespace AppBundle\Manager;


class MailManager
{
    private $mailer;
    private $serviceClient;
    private $container;

    public function __construct($mailer, $serviceClient, $container)
    {
        $this->mailer = $mailer;
        $this->serviceClient = $serviceClient;
        $this->container = $container;
    }

    public function prepareMail($booking)
    {
        $message = (new \Swift_Message('Billets du Musée du Louvre'))
            ->setFrom($this->serviceClient)
            ->setTo($booking->getEmail())
            ->setBody($this->container->get('templating')->render('Email/registration.html.twig', array(
                'booking' => $booking,
                'total' => $booking->getTotal())
        ),
        'text/html'
    );;

        return $message;
    }
}