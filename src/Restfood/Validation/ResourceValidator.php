<?php

namespace Restfood\Validation;

use Restfood\Entity\ResourceInterface;
use Restfood\Entity\ResourceRepositoryInterface;
use Restfood\Exception\InvalidDataException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ResourceValidator
{
    const NAME_MAX_LENGTH = 100;

    private $repository;
    private $validator;

    /**
     * ResourceValidator constructor.
     * @param ResourceRepositoryInterface $repository
     * @param ValidatorInterface $validator
     */
    public function __construct(ResourceRepositoryInterface $repository, ValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Validate the given data for the creation operation.
     *
     * @param array $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validateCreation(array $data)
    {
        $constraints = $this->getCreationConstraints();
        $violations = $this->validator->validateValue($data, $constraints);
        $this->assertEmpty($violations);
        return true;
    }

    /**
     * Validate the given data for the edition operation of the given target.
     *
     * @param ResourceInterface $targetResource
     * @param array $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validateEdition(ResourceInterface $targetResource, array $data)
    {
        $constraints = $this->getEditionConstraints($targetResource);
        $violations = $this->validator->validateValue($data, $constraints);
        $this->assertEmpty($violations);
        return true;
    }

    /**
     * Return the constraints to validate the data for the creation operation.
     *
     * @return Assert\Collection
     */
    private function getCreationConstraints()
    {
        return new Assert\Collection(array(
            'fields' => array(
                'name' => $this->getNameConstraints(),
            ),
        ));
    }

    /**
     * Return the constraints to validate the data for the edition operation of the given resource.
     *
     * @param ResourceInterface $resource
     * @return Assert\Collection
     */
    private function getEditionConstraints(ResourceInterface $resource)
    {
        return new Assert\Collection(array(
            'fields' => array(
                'id' => $this->getImmutableFieldConstraint($resource->obtainIdentifier()),
                'name' => $this->getNameConstraints($resource),
            ),
            'allowMissingFields' => true
        ));
    }

    /**
     * Return the constraints to validate the resource name.
     *
     * @param ResourceInterface $targetResource
     * @return Assert\Required(
     */
    private function getNameConstraints(ResourceInterface $targetResource = null)
    {
        return new Assert\Required(array(
            new Assert\Type(array('type' => 'string')),
            new Assert\NotBlank(),
            new Assert\Length(array('max' => self::NAME_MAX_LENGTH)),
            $this->getUniqueNameConstraint($targetResource)
        ));
    }

    /**
     * Return the constraint to validate the uniqueness of the name.
     *
     * @param ResourceInterface|null $targetResource
     * @return Assert\Callback
     */
    private function getUniqueNameConstraint(ResourceInterface $targetResource = null)
    {
        return new Assert\Callback(array(
            "methods"   =>  array(function ($name, ExecutionContext $context) use ($targetResource) {
                if ($this->existsAnotherResourceWithTheSameName($name, $targetResource)) {
                    $context->addViolation("Name already used");
                }
            }),
        ));
    }

    /**
     * Check if already exists an resource, different from the given one, with the given name.
     *
     * @param string $name
     * @param ResourceInterface|null $currentResource
     * @return bool
     */
    public function existsAnotherResourceWithTheSameName($name, ResourceInterface $currentResource = null)
    {
        $foundResource = $this->repository->findOneByName($name);
        if (!$foundResource) {
            return false;
        }
        return !$this->areTheSameObject($foundResource, $currentResource);

    }

    /**
     * Check if tow instances of resource belong to the same object.
     *
     * @param ResourceInterface $firstResource
     * @param ResourceInterface|null $secondResource
     * @return bool
     */
    private function areTheSameObject(ResourceInterface $firstResource, ResourceInterface $secondResource = null)
    {
        if (is_null($secondResource)) {
            return false;
        }
        return $firstResource->obtainIdentifier() === $secondResource->obtainIdentifier();
    }

    /**
     * Assert that there are no validation violations.
     *
     * @param ConstraintViolationListInterface $violations
     * @throws InvalidDataException
     */
    private function assertEmpty(ConstraintViolationListInterface $violations)
    {
        if (!$violations->count()) {
            return;
        }

        throw InvalidDataException::violateConstraints($violations);
    }

    /**
     * Return the constraint to validate a immutable field.
     *
     * @param mixed $fieldValue
     * @return Assert\Optional
     */
    private function getImmutableFieldConstraint($fieldValue)
    {
        return new Assert\Optional(array(
            new Assert\EqualTo(array(
                'value' => $fieldValue,
                'message' => 'This field can not be modified'
            ))
        ));
    }
}