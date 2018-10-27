<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 27/10/2018
 * Time: 12:54
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MaxNbrTicket extends Constraint
{
    public $message = 'La commande ne peut pas comporter plus de 6 billets';

}