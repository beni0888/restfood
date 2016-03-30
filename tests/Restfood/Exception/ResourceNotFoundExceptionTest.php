<?php

namespace Restfood\Exception;


class ResourceNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testIdentifierNotFound()
    {
        $resourceName = 'Resource';
        $sut = ResourceNotFoundException::identifierNotFound($resourceName, '1234');

        $this->assertInstanceOf('Restfood\Exception\ResourceNotFoundException', $sut);
        $this->assertEquals('Does not exist any Resource with the given identifier "1234"', $sut->getMessage());
    }
}
