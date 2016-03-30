<?php

namespace Restfood\RESTFul\Endpoint;

use Restfood\Json\JsonDecoder;
use Restfood\Manager\ResourceManagerInterface;
use Restfood\Test\MockFactory;
use Restfood\Test\ResourceFactory;
use Restfood\RESTFul\Url\UrlGeneratorInterface;
use Restfood\Validation\ResourceValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class AbstractResourceEndpointTest extends \PHPUnit_Framework_TestCase
{

    public function getResourceEndpoint(
        UrlGeneratorInterface $urlGenerator,
        JsonDecoder $jsonDecoder,
        ResourceManagerInterface $resourceManager,
        ResourceValidatorInterface $resourceValidator
    ) {
        return new ResourceEndpoint($urlGenerator, $jsonDecoder, $resourceManager, $resourceValidator);
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

    public function testCreateResource()
    {
        $urlGenerator = MockFactory::getUrlGenerator();
        $jsonDecoder = MockFactory::getJsonDecoder();
        $resourceManager = MockFactory::getResourceManager();
        $resourceValidator = MockFactory::getResourceValidator();
        $sut = $this->getResourceEndpoint($urlGenerator, $jsonDecoder, $resourceManager, $resourceValidator);

        $data = '{"name": "foo"}';
        $decodedData = ["name" => "foo"];
        $fakeResource = ResourceFactory::getResource('foo');
        $fakeResourceUrl = 'http://restfood/resource/1234';

        $jsonDecoder->shouldReceive('decode')->with($data)->andReturn($decodedData);
        $resourceManager->shouldReceive('create')->with($decodedData)->andReturn($fakeResource);
        $urlGenerator->shouldReceive('showResource')->with($fakeResource->obtainIdentifier())->andReturn($fakeResourceUrl);

        $result = $sut->createResource($data);

        $expectedContent = json_encode($fakeResource);
        $expectedStatus = 201;
        $expectedHeaders = ['content-type' => 'application/json', 'location' => $fakeResourceUrl];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);
    }

    public function testEditResource()
    {
        $urlGenerator = MockFactory::getUrlGenerator();
        $jsonDecoder = MockFactory::getJsonDecoder();
        $resourceManager = MockFactory::getResourceManager();
        $resourceValidator = MockFactory::getResourceValidator();
        $sut = $this->getResourceEndpoint($urlGenerator, $jsonDecoder, $resourceManager, $resourceValidator);

        $resourceId = '1234';
        $data = '{"name": "foo"}';
        $decodedData = ["name" => "bar"];
        $modifiedResource = $fakeResource = ResourceFactory::getResource('foo');
        $modifiedResource->setName('bar');

        $jsonDecoder->shouldReceive('decode')->with($data)->andReturn($decodedData);
        $resourceValidator->shouldReceive('assertExists')->with($resourceId);
        $resourceManager->shouldReceive('edit')->with($resourceId, $decodedData)->andReturn($modifiedResource);

        $result = $sut->editResource($resourceId, $data);

        $expectedContent = json_encode($modifiedResource);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);
    }

    public function testListResources()
    {
        $urlGenerator = MockFactory::getUrlGenerator();
        $jsonDecoder = MockFactory::getJsonDecoder();
        $resourceManager = MockFactory::getResourceManager();
        $resourceValidator = MockFactory::getResourceValidator();
        $sut = $this->getResourceEndpoint($urlGenerator, $jsonDecoder, $resourceManager, $resourceValidator);

        $fakeResources = ResourceFactory::getResourceList();

        $resourceManager->shouldReceive('findAll')->andReturn($fakeResources);

        $result = $sut->listResources();

        $expectedContent = json_encode($fakeResources);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);
    }

    public function testShowResource()
    {
        $urlGenerator = MockFactory::getUrlGenerator();
        $jsonDecoder = MockFactory::getJsonDecoder();
        $resourceManager = MockFactory::getResourceManager();
        $resourceValidator = MockFactory::getResourceValidator();
        $sut = $this->getResourceEndpoint($urlGenerator, $jsonDecoder, $resourceManager, $resourceValidator);

        $resourceId = '1234';
        $fakeResource = ResourceFactory::getResource('foo');

        $resourceValidator->shouldReceive('assertExists')->with($resourceId);
        $resourceManager->shouldReceive('findOne')->with($resourceId)->andReturn($fakeResource);

        $result = $sut->showResource($resourceId);

        $expectedContent = json_encode($fakeResource);
        $expectedStatus = 200;
        $expectedHeaders = ['content-type' => 'application/json'];
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus, $expectedHeaders);
    }

    public function testDeleteResource()
    {
        $urlGenerator = MockFactory::getUrlGenerator();
        $jsonDecoder = MockFactory::getJsonDecoder();
        $resourceManager = MockFactory::getResourceManager();
        $resourceValidator = MockFactory::getResourceValidator();
        $sut = $this->getResourceEndpoint($urlGenerator, $jsonDecoder, $resourceManager, $resourceValidator);

        $resourceId = '1234';

        $resourceValidator->shouldReceive('assertExists')->with($resourceId);
        $resourceManager->shouldReceive('remove')->with($resourceId);

        $result = $sut->deleteResource($resourceId);

        $expectedContent = '';
        $expectedStatus = 204;
        $this->assertHttpResponse($result, $expectedContent, $expectedStatus);
    }
}

class ResourceEndpoint extends AbstractResourceEndpoint {

    protected function getShowResourceUrl($identifier)
    {
        return $this->urlGenerator->showResource($identifier);
    }
}