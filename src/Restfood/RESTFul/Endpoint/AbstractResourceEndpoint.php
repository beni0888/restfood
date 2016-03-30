<?php

namespace Restfood\RESTFul\Endpoint;

use Restfood\Json\JsonDecoder;
use Restfood\Manager\ResourceManagerInterface;
use Restfood\RESTFul\Url\UrlGeneratorInterface;
use Restfood\Validation\ResourceValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResourceEndpoint
{
    protected $urlGenerator;
    private $jsonDecoder;
    private $resourceManager;
    private $resourceValidator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        JsonDecoder $jsonDecoder,
        ResourceManagerInterface $resourceManager,
        ResourceValidatorInterface $resourceValidator
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->jsonDecoder = $jsonDecoder;
        $this->resourceManager = $resourceManager;
        $this->resourceValidator = $resourceValidator;
    }

    public function createResource($jsonData)
    {
        $data = $this->jsonDecoder->decode($jsonData);
        $resource = $this->resourceManager->create($data);
        $url = $this->getShowResourceUrl($resource->obtainIdentifier());
        $httpCode = 201;
        $httpHeaders = array('Location' => $url);

        return new JsonResponse($resource, $httpCode, $httpHeaders);
    }

    public function editResource($identifier, $jsonData)
    {
        $jsonData = $this->jsonDecoder->decode($jsonData);
        $this->resourceValidator->assertExists($identifier);
        $resource = $this->resourceManager->edit($identifier, $jsonData);
        $httpCode = 200;

        return new JsonResponse($resource, $httpCode);
    }

    public function listResources()
    {
        $resources = $this->resourceManager->findAll();
        $httpCode = 200;

        return new JsonResponse($resources, $httpCode);
    }

    public function showResource($identifier)
    {
        $this->resourceValidator->assertExists($identifier);
        $resource = $this->resourceManager->findOne($identifier);
        $httpCode = 200;

        return new JsonResponse($resource, $httpCode);
    }

    public function deleteResource($identifier)
    {
        $this->resourceValidator->assertExists($identifier);
        $this->resourceManager->remove($identifier);
        $httpCode = 204;

        return new Response('', $httpCode);
    }

    protected abstract function getShowResourceUrl($identifier);
}