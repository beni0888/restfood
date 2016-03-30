<?php

namespace Restfood\Manager;

use Restfood\Entity\ResourceInterface;
use Restfood\Exception\InvalidDataException;
use Restfood\Exception\ResourceNotFoundException;

interface ResourceManagerInterface
{
    /**
     * Create a new resource with the given data.
     *
     * @param array $data
     * @return ResourceInterface
     * @throws InvalidDataException
     */
    public function create(array $data);

    /**
     * Return the resource with the given identifier.
     *
     * @param string $identifier
     * @return ResourceInterface
     * @throws ResourceNotFoundException
     */
    public function findOne($identifier);

    /**
     * Return a list with all the resources.
     *
     * @return ResourceInterface[]
     */
    public function findAll();

    /**
     * Update an existent resource with the given data
     *
     * @param string $identifier
     * @param array $data
     * @return ResourceInterface
     * @throws ResourceNotFoundException
     * @throws InvalidDataException
     */
    public function edit($identifier, array $data);

    /**
     * Remove the resource with the given identifier.
     *
     * @param $identifier
     * @throws ResourceNotFoundException
     */
    public function remove($identifier);
}