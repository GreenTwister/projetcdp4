<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 27/10/2018
 * Time: 12:56
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class MaxNbrTicketValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value > 6) {

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}