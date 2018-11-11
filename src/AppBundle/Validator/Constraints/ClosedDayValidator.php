<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 18/10/2018
 * Time: 12:48
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ClosedDayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \DateTime) {
            return;
        }
        $paque = date ('d-m', easter_date($value->format('Y')));
        $closedDays = ['01-01', '01-05','08-05','14-07','15-08', '01-11','11-05', '25-12', $paque];
        $day = $value->format('d-m');
        if (in_array($day , $closedDays)){

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}