<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 18/10/2018
 * Time: 11:17
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotTuesdayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \DateTime) {
            return;
        }

        $day = $value->format('D');
        if ($day == "Tue") {

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}