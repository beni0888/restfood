<?php

namespace Restfood\RESTFul\Endpoint;


use Restfood\Json\JsonDecoder;
use Restfood\Manager\DishIngredientManagerInterface;
use Restfood\Test\MockFactory;
use Restfood\Test\ResourceFactory;
use Restfood\Validation\ResourceValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class DishIngredientEndpointTest extends \PHPUnit_Framework_TestCase
{
    public function testSetDishIngredients()
    {
        $jsonDecoder = MockFactory::getJsonDecoder();
        $dishIngredientManager = MockFactory::getDishIngredientManager();
        $dishValidator = MockFactory::getDishValidator();
        $sut = $this->getDishIngredientEndpoint($jsonDecoder, $dishIngredientManager, $dishValidator);

        $dishId = '1234';
        $data = '[1, 2, 3]';
        $ingredientIds = [1, 2, 3];
        $fakeIngredients = ResourceFactory::getIngredientList();

        $jsonDecoder->shouldReceive('decode')->with($data)->andReturn($ingredientIds);
        $dishValidator->shouldReceive('assertExists')->with($dishId);
        $dishIngredientManager->shouldReceive('setDishIngredients')->with($dishId, $ingredientIds)->andReturn($fakeIngredients);

        $result = $sut->setDishIngredients($dishId, $data);

        $expectedContent = json_encode($fakeIngredients);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);
    }

    public function testListDishIngredients()
    {
        $jsonDecoder = MockFactory::getJsonDecoder();
        $dishIngredientManager = MockFactory::getDishIngredientManager();
        $dishValidator = MockFactory::getDishValidator();
        $sut = $this->getDishIngredientEndpoint($jsonDecoder, $dishIngredientManager, $dishValidator);

        $dishId = '1234';
        $fakeIngredients = ResourceFactory::getIngredientList();

        $dishValidator->shouldReceive('assertExists')->with($dishId);
        $dishIngredientManager->shouldReceive('findDishIngredients')->with($dishId)->andReturn($fakeIngredients);

        $result = $sut->listDishIngredients($dishId);

        $expectedContent = json_encode($fakeIngredients);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);
    }

    /**
     * @return DishIngredientEndpoint
     */
    private function getDishIngredientEndpoint(
        JsonDecoder $jsonDecoder,
        DishIngredientManagerInterface $dishIngredientManager,
        ResourceValidatorInterface $dishValidator
    )
    {
        return new DishIngredientEndpoint($jsonDecoder, $dishIngredientManager, $dishValidator);
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
