<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 04/11/2018
 * Time: 13:35
 */

namespace Tests\AppBundle\Validator\Constraints;


use AppBundle\Validator\Constraints\NotSunday;
use AppBundle\Validator\Constraints\NotSundayValidator;
use Tests\ValidatorTestAbstract;

class NotSundayValidatorTest extends ValidatorTestAbstract
{
    protected function getValidatorInstance()
    {
        return new NotSundayValidator();
    }


    /**
 * @dataProvider  dayOk
 */
    public function testValidationOk($day)
    {
        $notSundayConstraint = new NotSunday();
        $notSundayValidator = $this->initValidator();

        $notSundayValidator->validate(\DateTime::createFromFormat('D',$day), $notSundayConstraint);

    }

    public function dayOk()
    {
        return [
            ['Mon'],
            ['Tue'],
            ['Wed'],
            ['Thu'],
            ['Fri'],
            ['Sat'],
        ];
    }

    /**
     * @dataProvider  dayKo
     */
    public function testValidationKo($day)
    {
        $notSundayConstraint = new NotSunday();

        $notSundayValidator = $this->initValidator($notSundayConstraint->message);
        $notSundayValidator->validate(\DateTime::createFromFormat('D', $day), $notSundayConstraint);

    }

    public function dayKo()
    {
        return [
            ['Sun'],
        ];
    }
}