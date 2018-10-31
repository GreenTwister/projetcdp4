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

    public function __construct($mailer, $serviceClient)
    {
        $this->mailer = $mailer;
        $this->serviceClient = $serviceClient;
    }

    public function prepareMail($booking)
    {
        $message = (new \Swift_Message('Billets du Musée du Louvre'))
            ->setFrom($this->serviceClient)
            ->setTo($booking->getEmail());

        return $message;
    }
}