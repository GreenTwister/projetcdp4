<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 27/10/2018
 * Time: 13:07
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoNegativeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value < 1) {

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}