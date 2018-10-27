<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 27/10/2018
 * Time: 13:06
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoNegative extends Constraint
{
    public $message = 'Le nombre de billet n\'est pas valide ';
}