<?php

namespace Restfood\Validation;

use Restfood\Entity\ResourceInterface;
use Restfood\Exception\InvalidDataException;
use Restfood\Exception\ResourceNotFoundException;

interface ResourceValidatorInterface
{
    /**
     * Check that the given identifier belongs to an existent resource.
     *
     * @param string $identifier
     * @return bool
     * @throws ResourceNotFoundException
     */
    public function assertExists($identifier);

    /**
     * Validate the given data for the resource creation operation.
     *
     * @param array $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validateCreation(array $data);

    /**
     * Validate the given data for the edition operation of the given target.
     *
     * @param ResourceInterface $targetResource
     * @param array $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validateEdition(ResourceInterface $targetResource, array $data);
}