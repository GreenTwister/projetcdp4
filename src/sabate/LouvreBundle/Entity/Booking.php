<?php

namespace sabate\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="sabate\LouvreBundle\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateVisit", type="datetime")
     */
    private $dateVisit;

    /**
     * @var int
     *
     * @ORM\Column(name="NbrTicket", type="integer")
     */
    private $nbrTicket;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="NumBooking", type="integer", unique=true)
     */
    private $numBooking;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateVisit
     *
     * @param \DateTime $dateVisit
     *
     * @return Booking
     */
    public function setDateVisit($dateVisit)
    {
        $this->dateVisit = $dateVisit;

        return $this;
    }

    /**
     * Get dateVisit
     *
     * @return \DateTime
     */
    public function getDateVisit()
    {
        return $this->dateVisit;
    }

    /**
     * Set nbrTicket
     *
     * @param integer $nbrTicket
     *
     * @return Booking
     */
    public function setNbrTicket($nbrTicket)
    {
        $this->nbrTicket = $nbrTicket;

        return $this;
    }

    /**
     * Get nbrTicket
     *
     * @return int
     */
    public function getNbrTicket()
    {
        return $this->nbrTicket;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Booking
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set numBooking
     *
     * @param integer $numBooking
     *
     * @return Booking
     */
    public function setNumBooking($numBooking)
    {
        $this->numBooking = $numBooking;

        return $this;
    }

    /**
     * Get numBooking
     *
     * @return int
     */
    public function getNumBooking()
    {
        return $this->numBooking;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Booking
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }
}
