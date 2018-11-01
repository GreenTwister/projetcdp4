<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 01/11/2018
 * Time: 11:25
 */

namespace AppBundle\Service;



use Symfony\Component\HttpFoundation\RequestStack;

class StripeService implements PaymentInterface
{

    private $stripeSecretKey;

    /** @var null|\Symfony\Component\HttpFoundation\Request  */
    private $request;

    public function __construct(RequestStack $requestStack, $stripeSecretKey)
    {

        $this->stripeSecretKey = $stripeSecretKey;
        $this->request = $requestStack->getCurrentRequest();
    }


    public function doPayment($amount, $desc)
    {
        $token = $this->request->request->get('stripeToken');

        \Stripe\Stripe::setApiKey($this->stripeSecretKey);
        try{
            \Stripe\Charge::create(array(
                "amount" => $amount * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => $desc
            ));

        }catch(\Exception $e){
            return false;
        }


        return true;
    }
}