<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 18/10/2018
 * Time: 11:17
 */

namespace AppBundle\Validator\Constraints;


use AppBundle\Entity\Booking;
use AppBundle\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotFullCapacityValidator extends ConstraintValidator
{
    /**
     * @var BookingRepository
     */
    private $bookingRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->bookingRepository = $entityManager->getRepository(Booking::class);
    }


    public function validate($object, Constraint $constraint)
    {
        if (!$object instanceof Booking) {
            return;
        }

        $nbTicketsReserved = $this->bookingRepository->countNbTicketPerDate($object->getDateVisit());



//        $today = new \DateTime();
//        // Si billet journée alors qu'il est plus de 14h
//        $hour = $today->format('H');
//        $today = $today->format('d/m/y');
//        $dateBooking = $object->getDateVisit()->format('d/m/y');
//        if ($today == $dateBooking && $object->getTypeTicket() == 'Journée' && (int)$hour >= 11) {
//            $this->context->buildViolation($constraint->message)
//                ->atPath('typeTicket')
//                ->addViolation();
//        }
    }
}