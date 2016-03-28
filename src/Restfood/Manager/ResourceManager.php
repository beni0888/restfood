<?php

namespace Restfood\Manager;

use Restfood\Entity\ResourceInterface;
use Restfood\Entity\ResourceRepositoryInterface;
use Restfood\Exception\InvalidDataException;
use Restfood\Exception\ResourceNotFoundException;
use Restfood\Validation\ResourceValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Json\DecodingFailedException;
use Webmozart\Json\JsonDecoder;
use InvalidArgumentException;

class ResourceManager
{
    private $resourceClass;
    private $resourceName;
    private $repository;
    private $validator;
    private $resource;

    /**
     * ResourceManager constructor.
     *
     * @param string $resourceClass
     * @param string $resourceName
     * @param ResourceRepositoryInterface $repository
     * @param ResourceValidator $validator
     */
    public function __construct(
        $resourceClass,
        $resourceName,
        ResourceRepositoryInterface $repository,
        ResourceValidator $validator
    ) {
        $this->resourceClass = $resourceClass;
        $this->resourceName = $resourceName;
        $this->repository = $repository;
        $this->validator = $validator;
        $this->resource = null;
    }

    /**
     * Create a new resource with the given data.
     *
     * @param string $data
     * @return ResourceInterface
     * @throws InvalidDataException
     */
    public function create($data)
    {
        $data = $this->sanitizeResourceData($data);
        $this->validator->validateCreation($data);
        $resource = $this->createObjectFromArray($data);
        $resource = $this->repository->save($resource);
        return $resource;
    }

    /**
     * Return the information of the resource with the given identifier.
     *
     * @param string $identifier
     * @return mixed
     */
    public function show($identifier)
    {
        $resource = $this->repository->findOneByIdentifier($identifier);
        if (!$resource) {
            throw ResourceNotFoundException::identifierNotFound($this->resourceName, $identifier);
        }
        return $resource;
    }

    /**
     * Return a list with all the resources.
     *
     * @return ResourceInterface[]
     */
    public function showList()
    {
        return $this->repository->findAll();
    }

    /**
     * Update an existent resource with the given data
     *
     * @param string $identifier
     * @param array $data
     * @return ResourceInterface
     * @throws ResourceNotFoundException
     * @throws InvalidDataException
     */
    public function edit($identifier, $data)
    {
        $data = $this->sanitizeResourceData($data);
        $this->assertExists($identifier);

        $resource = $this->repository->findOneByIdentifier($identifier);
        $this->validator->validateEdition($resource, $data);

        $resource = $this->updateInformation($resource, $data);
        $this->repository->update($resource);
        return $resource;
    }

    /**
     * Remove the resource with the given identifier.
     *
     * @param $identifier
     */
    public function remove($identifier)
    {
        $resource = $this->repository->findOneByIdentifier($identifier);
        if (!$resource) {
            throw ResourceNotFoundException::identifierNotFound($this->resourceName, $identifier);
        }
        $this->repository->remove($resource);
    }

    /**
     * Check that the given identifier belongs to an existent resource
     *
     * @param $identifier
     * @return bool
     */
    private function assertExists($identifier)
    {
        $resource = $this->repository->findOneByIdentifier($identifier);
        if (is_null($resource)) {
            throw ResourceNotFoundException::identifierNotFound($this->resourceName, $identifier);
        }
        return true;
    }

    /**
     * Return an ResourceInterface object with the given data.
     *
     * @param array $resourceData
     * @return ResourceInterface
     */
    private function createObjectFromArray(array $resourceData)
    {
        $class = $this->resourceClass;
        return new $class($resourceData['name']);
    }

    /**
     * Update the given resource object with the given data.
     *
     * @param ResourceInterface $resource
     * @param array $data
     * @return ResourceInterface
     */
    private function updateInformation(ResourceInterface $resource, array $data)
    {
        $resource->setName($data['name']);
        return $resource;
    }

    /**
     * Sanitize the given resource data.
     *
     * @param string $json
     * @return array
     */
    private function sanitizeResourceData($json)
    {
        try {
            \Webmozart\Assert\Assert::string($json);
            $decoder = new JsonDecoder();
            return (array)$decoder->decode($json);
        }
        catch (InvalidArgumentException $e) {}
        catch (DecodingFailedException $e) {}

        throw InvalidDataException::invalidJson();
    }
}