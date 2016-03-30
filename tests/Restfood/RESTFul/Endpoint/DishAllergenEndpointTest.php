<?php

namespace Restfood\RESTFul\Endpoint;


use Restfood\Manager\DishAllergenManager;
use Restfood\Test\MockFactory;
use Restfood\Test\ResourceFactory;
use Restfood\Validation\ResourceValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class DishAllergenEndpointTest extends \PHPUnit_Framework_TestCase
{
    public function testListDishesWithAllergen()
    {
        $dishAllergenManager = MockFactory::getDishAllergenManager();
        $allergenValidator = MockFactory::getAllergenValidator();
        $dishValidator = MockFactory::getDishValidator();
        $sut = $this->getDishAllergenEndpoint($dishAllergenManager, $allergenValidator, $dishValidator);

        $allergenId = '1234';
        $fakeDishes = ResourceFactory::getDishList();

        $allergenValidator->shouldReceive('assertExists')->with($allergenId);
        $dishAllergenManager->shouldReceive('getDishesWithAllergen')->with($allergenId)->andReturn($fakeDishes);

        $result = $sut->listDishesWithAllergen($allergenId);

        $expectedContent = json_encode($fakeDishes);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);

    }

    public function testListAllergensInDish()
    {
        $dishAllergenManager = MockFactory::getDishAllergenManager();
        $allergenValidator = MockFactory::getAllergenValidator();
        $dishValidator = MockFactory::getDishValidator();
        $sut = $this->getDishAllergenEndpoint($dishAllergenManager, $allergenValidator, $dishValidator);

        $allergenId = '1234';
        $fakeAllergens = ResourceFactory::getAllergenList();

        $dishValidator->shouldReceive('assertExists')->with($allergenId);
        $dishAllergenManager->shouldReceive('getAllergensInDish')->with($allergenId)->andReturn($fakeAllergens);

        $result = $sut->listAllergensInDish($allergenId);

        $expectedContent = json_encode($fakeAllergens);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);

    }

    /**
     * @return DishAllergenEndpoint
     */
    private function getDishAllergenEndpoint(
        DishAllergenManager $dishAllergenManager,
        ResourceValidatorInterface $allergenValidator,
        ResourceValidatorInterface $dishValidator
    ) {
        return new DishAllergenEndpoint($dishAllergenManager, $allergenValidator, $dishValidator);
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
