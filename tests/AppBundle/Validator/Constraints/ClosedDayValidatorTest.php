<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 01/11/2018
 * Time: 14:39
 */

namespace Tests\AppBundle\Validator\Constraints;

use AppBundle\Validator\Constraints\ClosedDay;
use AppBundle\Validator\Constraints\ClosedDayValidator;
use Tests\ValidatorTestAbstract;

class ClosedDayValidatorTest extends ValidatorTestAbstract
{
    /**
     * @dataProvider dateKo
     */
    public function testValidationKo($date)
    {
        $closeDayConstraint = new ClosedDay();
        $closeDayValidator = $this->initValidator($closeDayConstraint->message);

        $closeDayValidator->validate(new \DateTime($date), $closeDayConstraint);

    }

    public function dateKo()
    {
        return [
            ['2018-05-01'],
            ['2018-12-25'],
            ['2018-11-01'],
        ];
    }

    /**
     * @dataProvider dateOk
     */
    public function testValidationOk($date)
    {

        $closeDayConstraint = new ClosedDay();
        $closeDayValidator = $this->initValidator();
        $closeDayValidator->validate(new \DateTime($date), $closeDayConstraint);

    }

    public function dateOk()
    {
        return [
            ['2018-05-12'],
        ];
    }

    protected function getValidatorInstance()
    {
        return new ClosedDayValidator();
    }


}