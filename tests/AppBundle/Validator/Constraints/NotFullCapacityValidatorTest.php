<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 10/11/2018
 * Time: 15:54
 */

namespace Tests\AppBundle\Validator\Constraints;


use AppBundle\Repository\BookingRepository;
use AppBundle\Validator\Constraints\NotFullCapacity;
use AppBundle\Validator\Constraints\NotFullCapacityValidator;
use Doctrine\ORM\EntityManager;
use Tests\ValidatorTestAbstract;

class NotFullCapacityValidatorTest extends ValidatorTestAbstract
{
    protected function getValidatorInstance()
    {
        $em = $this->getMockBuilder(EntityManager::class)
        ->disableOriginalConstructor()
            ->getMock();
        return new NotFullCapacityValidator($em);
    }

    /**
     * @dataProvider TicketsOk
     */
    public function testValidationOk($nbTickets)
    {
        $notFullCapacityConstraint = new NotFullCapacity();
        $notFullCapacityValidator = $this->initValidator();
        $notFullCapacityValidator->validate($nbTickets, $notFullCapacityConstraint);

    }

    public function TicketsOk()
    {
        return [
            ['6'],
            ['2'],
            ['4'],
        ];
    }

}