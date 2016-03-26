<?php

namespace Restfood\Exception;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class InvalidDataExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testViolateConstraints()
    {
        $violations = new ConstraintViolationList([
            $this->getViolation('foo', 'This field is invalid for some reason'),
            $this->getViolation('bar', 'This one is invalid for another reason'),
        ]);
        $sut = InvalidDataException::violateConstraints($violations);

        $this->assertInstanceOf('Restfood\Exception\InvalidDataException', $sut);
        $expectedMessage = <<<EOT
Given data is not valid:
- foo: This field is invalid for some reason
- bar: This one is invalid for another reason

EOT;
        $this->assertEquals($expectedMessage, $sut->getMessage());
    }

    public function testInvalidJson()
    {
        $sut = InvalidDataException::invalidJson();

        $this->assertInstanceOf('Restfood\Exception\InvalidDataException', $sut);
        $expectedMessage = 'Given data is not a valid json document';
        $this->assertEquals($expectedMessage, $sut->getMessage());
    }

    private function getViolation($propertyPath, $message)
    {
        return new ConstraintViolation($message, $message, array(), null, $propertyPath, null);
    }
}