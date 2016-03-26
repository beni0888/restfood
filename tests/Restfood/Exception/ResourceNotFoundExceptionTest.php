<?php

namespace Restfood\Exception;


class ResourceNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testIdentifierNotFound()
    {
        $sut = ResourceNotFoundException::identifierNotFound('Foo', '1234');

        $this->assertInstanceOf('Restfood\Exception\ResourceNotFoundException', $sut);
        $this->assertEquals('Does not exist any Foo with the given identifier "1234"', $sut->getMessage());
    }
}
