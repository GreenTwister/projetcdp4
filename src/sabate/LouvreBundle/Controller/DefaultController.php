<?php

namespace sabate\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('@sabateLouvre/Louvre/index.html.twig');
    }

    /**
     * @Route("/tickets", name="tickets")
     */
    public function ticketsAction()
    {
        return $this->render('@sabateLouvre/Louvre/tickets.html.twig');
    }

    /**
     * @Route("/recap", name="recap")
     */
    public function recapAction()
    {
        return $this->render('@sabateLouvre/Louvre/recap.html.twig');
    }
}
