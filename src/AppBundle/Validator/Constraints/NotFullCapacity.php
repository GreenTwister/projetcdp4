<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotFullCapacity extends Constraint
{
    public $message = 'Plus de place disponible pour ce jour';

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }


}