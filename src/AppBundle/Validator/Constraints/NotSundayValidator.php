<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 18/10/2018
 * Time: 12:42
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotSundayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \DateTime) {
            return;
        }

        $day = $value->format('D');
        if ($day == "Sun") {

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}