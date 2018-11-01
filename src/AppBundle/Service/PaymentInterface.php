<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 01/11/2018
 * Time: 11:35
 */

namespace AppBundle\Service;


interface PaymentInterface
{
    public function doPayment($amount, $desc);
}