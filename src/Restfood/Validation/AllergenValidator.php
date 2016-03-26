<?php

namespace Restfood\Validation;

use Restfood\Entity\Allergen;
use Restfood\Entity\AllergenRepositoryInterface;
use Restfood\Exception\InvalidDataException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AllergenValidator
{
    const NAME_MAX_LENGTH = 100;

    private $repository;
    private $validator;

    /**
     * AllergenValidator constructor.
     * @param AllergenRepositoryInterface $repository
     * @param ValidatorInterface $validator
     */
    public function __construct(AllergenRepositoryInterface $repository, ValidatorInterface $validator)
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
     * @param Allergen $targetAllergen
     * @param array $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validateEdition(Allergen $targetAllergen, array $data)
    {
        $constraints = $this->getEditionConstraints($targetAllergen);
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
     * Return the constraints to validate the data for the edition operation of the given allergen.
     *
     * @param Allergen $allergen
     * @return Assert\Collection
     */
    private function getEditionConstraints(Allergen $allergen)
    {
        return new Assert\Collection(array(
            'fields' => array(
                'id' => $this->getImmutableFieldConstraint($allergen->obtainIdentifier()),
                'name' => $this->getNameConstraints($allergen),
            ),
            'allowMissingFields' => true
        ));
    }

    /**
     * Return the constraints to validate the allergen name.
     *
     * @param Allergen $targetAllergen
     * @return Assert\Required(
     */
    private function getNameConstraints(Allergen $targetAllergen = null)
    {
        return new Assert\Required(array(
            new Assert\Type(array('type' => 'string')),
            new Assert\NotBlank(),
            new Assert\Length(array('max' => self::NAME_MAX_LENGTH)),
            $this->getUniqueNameConstraint($targetAllergen)
        ));
    }

    /**
     * Return the constraint to validate the uniqueness of the name.
     *
     * @param Allergen|null $targetAllergen
     * @return Assert\Callback
     */
    private function getUniqueNameConstraint(Allergen $targetAllergen = null)
    {
        return new Assert\Callback(array(
            "methods"   =>  array(function ($name, ExecutionContext $context) use ($targetAllergen) {
                if ($this->existsAnotherAllergenWithTheSameName($name, $targetAllergen)) {
                    $context->addViolation("Name already used");
                }
            }),
        ));
    }

    /**
     * Check if already exists an allergen, different from the given one, with the given name.
     *
     * @param string $name
     * @param Allergen|null $currentAllergen
     * @return bool
     */
    public function existsAnotherAllergenWithTheSameName($name, Allergen $currentAllergen = null)
    {
        $foundAllergen = $this->repository->findOneByName($name);
        if (!$foundAllergen) {
            return false;
        }
        return !$this->areTheSameObject($foundAllergen, $currentAllergen);

    }

    /**
     * Check if tow instances of allergen belong to the same object.
     *
     * @param Allergen $firstAllergen
     * @param Allergen|null $secondAllergen
     * @return bool
     */
    private function areTheSameObject(Allergen $firstAllergen, Allergen $secondAllergen = null)
    {
        if (is_null($secondAllergen)) {
            return false;
        }
        return $firstAllergen->obtainIdentifier() === $secondAllergen->obtainIdentifier();
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