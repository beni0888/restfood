<?php

namespace Restfood\RESTFul\Endpoint;

use Restfood\Json\JsonDecoder;
use Restfood\Manager\IngredientAllergenManagerInterface;
use Restfood\Test\MockFactory;
use Restfood\Test\ResourceFactory;
use Restfood\Validation\ResourceValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class IngredientAllergenEndpointTest extends \PHPUnit_Framework_TestCase
{
    public function testSetIngredientAllergens()
    {
        $jsonDecoder = MockFactory::getJsonDecoder();
        $ingredientAllergenManager = MockFactory::getIngredientAllergenManager();
        $ingredientValidator = MockFactory::getIngredientValidator();
        $sut = $this->getIngredientAllergenEndpoint($jsonDecoder, $ingredientAllergenManager, $ingredientValidator);

        $ingredientId = '1234';
        $data = "[1, 2, 3]";
        $allergenIds = [1,2,3];
        $fakeAllergens = ResourceFactory::getAllergenList();

        $jsonDecoder->shouldReceive('decode')->with($data)->andReturn($allergenIds);
        $ingredientValidator->shouldReceive('assertExists')->with($ingredientId);
        $ingredientAllergenManager->shouldReceive('setIngredientAllergens')->with($ingredientId, $allergenIds)->andReturn($fakeAllergens);

        $result = $sut->setIngredientAllergens($ingredientId, $data);

        $expectedContent = json_encode($fakeAllergens);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);
    }

    public function testListIngredientAllergens()
    {
        $jsonDecoder = MockFactory::getJsonDecoder();
        $ingredientAllergenManager = MockFactory::getIngredientAllergenManager();
        $ingredientValidator = MockFactory::getIngredientValidator();
        $sut = $this->getIngredientAllergenEndpoint($jsonDecoder, $ingredientAllergenManager, $ingredientValidator);

        $ingredientId = '1234';
        $fakeAllergens = ResourceFactory::getAllergenList();

        $ingredientValidator->shouldReceive('assertExists')->with($ingredientId);
        $ingredientAllergenManager->shouldReceive('findIngredientAllergens')->with($ingredientId)->andReturn($fakeAllergens);

        $result = $sut->listIngredientAllergens($ingredientId);

        $expectedContent = json_encode($fakeAllergens);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);
    }

    /**
     * @return IngredientAllergenEndpoint
     */
    private function getIngredientAllergenEndpoint(
        JsonDecoder $jsonDecoder,
        IngredientAllergenManagerInterface $ingredientAllergenManager,
        ResourceValidatorInterface $ingredientValidator
    )
    {
        return new IngredientAllergenEndpoint($jsonDecoder, $ingredientAllergenManager, $ingredientValidator);
    }

    private function assertHttpResponse($response, $content, $status, array $headers = [], $class = Response::class)
    {
        $this->assertInstanceOf($class, $response, 'Obtained response object does not match the expected class');
        $this->assertEquals($status, $response->getStatusCode(), 'Obtained response status code is not the expected one');
        $this->assertEquals($content, $response->getContent(), 'Obtained response content is not as expected');

        $messagePattern = 'Obtained response "%s" header does not have the expected value';
        foreach ($headers as $key => $value) {
            $this->assertEquals($value, $response->headers->get($key), sprintf($messagePattern, $key));
        }
    }
}
