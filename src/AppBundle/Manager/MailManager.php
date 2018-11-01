<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 31/10/2018
 * Time: 12:24
 */

namespace AppBundle\Manager;


use Twig\Environment;

class MailManager
{
    private $mailer;
    private $serviceClient;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, $serviceClient, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->serviceClient = $serviceClient;
        $this->twig = $twig;
    }

    public function prepareMail($booking)
    {
        $message = (new \Swift_Message('Billets du Musée du Louvre'))
            ->setFrom($this->serviceClient)
            ->setTo($booking->getEmail())
            ->setBody($this->twig->render('Email/registration.html.twig', array(
                'booking' => $booking,
                'total' => $booking->getTotal())
            ),
        'text/html'
        );

        return $this->mailer->send($message);
    }
}