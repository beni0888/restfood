<?php

namespace Restfood\Manager;

use Restfood\Entity\Allergen;
use Restfood\Entity\AllergenRepositoryInterface;
use Restfood\Exception\InvalidDataException;
use Restfood\Exception\ResourceNotFoundException;
use Restfood\Validation\AllergenValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Json\DecodingFailedException;
use Webmozart\Json\JsonDecoder;
use InvalidArgumentException;

class AllergenManager
{
    const RESOURCE_NAME = 'Allergen';

    private $repository;
    private $validator;
    private $allergen;

    /**
     * AllergenManager constructor.
     *
     * @param AllergenRepositoryInterface $repository
     * @param AllergenValidator $validator
     */
    public function __construct(AllergenRepositoryInterface $repository, AllergenValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->allergen = null;
    }

    /**
     * Create a new allergen with the given data.
     *
     * @param string $data
     * @return Allergen
     * @throws InvalidDataException
     */
    public function create($data)
    {
        $data = $this->sanitizeAllergenData($data);
        $this->validator->validateCreation($data);
        $allergen = $this->createObjectFromArray($data);
        $allergen = $this->repository->save($allergen);
        return $allergen;
    }

    /**
     * Return the information of the allergen with the given identifier.
     *
     * @param string $identifier
     * @return mixed
     */
    public function show($identifier)
    {
        $allergen = $this->repository->findOneByIdentifier($identifier);
        if (!$allergen) {
            throw ResourceNotFoundException::identifierNotFound(self::RESOURCE_NAME, $identifier);
        }
        return $allergen;
    }

    /**
     * Return a list with all the allergens.
     *
     * @return Allergen[]
     */
    public function showList()
    {
        return $this->repository->findAll();
    }

    /**
     * Update an existent allergen with the given data
     *
     * @param string $identifier
     * @param array $data
     * @return Allergen
     * @throws ResourceNotFoundException
     * @throws InvalidDataException
     */
    public function edit($identifier, $data)
    {
        $data = $this->sanitizeAllergenData($data);
        $this->assertExists($identifier);

        $allergen = $this->repository->findOneByIdentifier($identifier);
        $this->validator->validateEdition($allergen, $data);

        $allergen = $this->updateInformation($allergen, $data);
        $this->repository->update($allergen);
        return $allergen;
    }

    /**
     * Remove the allergen with the given identifier.
     *
     * @param $identifier
     */
    public function remove($identifier)
    {
        $allergen = $this->repository->findOneByIdentifier($identifier);
        if (!$allergen) {
            throw ResourceNotFoundException::identifierNotFound(self::RESOURCE_NAME, $identifier);
        }
        $this->repository->remove($allergen);
    }

    /**
     * Check that the given identifier belongs to an existent allergen
     *
     * @param $identifier
     * @return bool
     */
    private function assertExists($identifier)
    {
        $allergen = $this->repository->findOneByIdentifier($identifier);
        if (is_null($allergen)) {
            throw ResourceNotFoundException::identifierNotFound(self::RESOURCE_NAME, $identifier);
        }
        return true;
    }

    /**
     * Return an Allergen object with the given data.
     *
     * @param array $allergenData
     * @return Allergen
     */
    private function createObjectFromArray(array $allergenData)
    {
        return new Allergen($allergenData['name']);
    }

    /**
     * Update the given allergen object with the given data.
     *
     * @param Allergen $allergen
     * @param array $data
     * @return Allergen
     */
    private function updateInformation(Allergen $allergen, array $data)
    {
        $allergen->setName($data['name']);
        return $allergen;
    }

    /**
     * Sanitize the given allergen data.
     *
     * @param string $json
     * @return array
     */
    private function sanitizeAllergenData($json)
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