<?php

namespace Restfood\Json;

class JsonDecoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return JsonDecoder
     */
    private function getDecoder()
    {
        return new JsonDecoder();
    }

    /**
     * @dataProvider providerForTestExceptionIsThrownWhenDataIsNotString
     * @expectedException \Restfood\Exception\InvalidDataException
     */
    public function testExceptionIsThrownWhenDataIsNotString($data)
    {
        $sut = $this->getDecoder();

        $sut->decode($data);

        $this->fail('You should never reach this point');
    }

    public function providerForTestExceptionIsThrownWhenDataIsNotString()
    {
        return [
            'null' => [null],
            'integer' => [1],
            'array' => [1,2,3],
            'object' => [(object)['property' => 1, 'foo' => 'var']],
        ];
    }

    /**
     * @expectedException \Restfood\Exception\InvalidDataException
     */
    public function testExceptionIsThrownWhenDataIsNotAValidJsonDocument()
    {
        $sut = $this->getDecoder();
        $data = '[ 1234';

        var_dump($sut->decode($data));

        $this->fail('You should never reach this point');
    }

    public function testDecode()
    {
        $sut = $this->getDecoder();
        $data = '{ "foo": "bar" }';

        $result = $sut->decode($data);
        $expected = ['foo' => 'bar'];

        $this->assertEquals($expected, $result, 'Decoded data is not as expected');
    }


}
