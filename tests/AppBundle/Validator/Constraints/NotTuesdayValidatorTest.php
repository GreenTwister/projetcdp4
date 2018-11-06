<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 06/11/2018
 * Time: 13:13
 */

namespace Tests\AppBundle\Validator\Constraints;


use AppBundle\Validator\Constraints\NotTuesday;
use AppBundle\Validator\Constraints\NotTuesdayValidator;
use Tests\ValidatorTestAbstract;

class NotTuesdayValidatorTest extends ValidatorTestAbstract
{
    protected function getValidatorInstance()
    {
        return new NotTuesdayValidator();
    }


    /**
     * @dataProvider  dayOk
     */
    public function testValidationOk($day)
    {
        $notTuesdayConstraint = new NotTuesday();
        $notTuesdayValidator = $this->initValidator();

        $notTuesdayValidator->validate(\DateTime::createFromFormat('D',$day), $notTuesdayConstraint);

    }

    public function dayOk()
    {
        return [
            ['Mon'],
            ['Wed'],
            ['Thu'],
            ['Fri'],
            ['Sat'],
            ['Sun']
        ];
    }

    public function testValidationKo()
    {
        $notTuesdayConstraint = new NotTuesday();

        $notTuesdayValidator = $this->initValidator($notTuesdayConstraint->message);
        $notTuesdayValidator->validate(\DateTime::createFromFormat('D', 'Tue'), $notTuesdayConstraint);

    }

}