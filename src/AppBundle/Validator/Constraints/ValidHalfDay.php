<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidHalfDay extends Constraint
{
    public $message = 'Passé 14h vous ne pouvez pas sélectionner journée entière pour aujourd\'hui';

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }


}