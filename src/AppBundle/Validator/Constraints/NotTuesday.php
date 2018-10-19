<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotTuesday extends Constraint
{
    public $message = 'La date ne peut pas être un mardi';
}