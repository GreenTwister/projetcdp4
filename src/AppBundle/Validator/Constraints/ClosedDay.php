<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 18/10/2018
 * Time: 12:47
 */

namespace AppBundle\Validator\Constraints;

/**
 * @Annotation
 */
use Symfony\Component\Validator\Constraint;

class ClosedDay extends Constraint
{
 public $message = " Le musée est fermé à cette date , impossible de commander un billet.";
}