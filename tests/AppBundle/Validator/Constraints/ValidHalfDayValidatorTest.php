<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 06/11/2018
 * Time: 13:20
 */

namespace Tests\AppBundle\Validator\Constraints;


use AppBundle\Validator\Constraints\ValidHalfDay;
use AppBundle\Validator\Constraints\ValidHalfDayValidator;
use Tests\ValidatorTestAbstract;

class ValidHalfDayValidatorTest extends ValidatorTestAbstract
{
    protected function getValidatorInstance()
    {
        return new ValidHalfDayValidator();
    }

    /**
     * @dataProvider  hourOk
     */
    public function testValidationOk($hour)
    {
        $validHalfConstraint = new ValidHalfDay();
        $validHalfValidator = $this->initValidator();
        $validHalfValidator->validate(\DateTime::createFromFormat('H', $hour), $validHalfConstraint);

    }

    public function hourOk()
    {
        return [
            ['0'],
            ['1'],
            ['2'],
            ['3'],
            ['4'],
            ['5'],
            ['6'],
            ['7'],
            ['8'],
            ['9'],
            ['10'],
            ['11'],
            ['12'],
            ['13'],
        ];
    }

}