<?php

namespace Restfood\Validation;

use Mockery as m;
use Restfood\Entity\Allergen;
use Symfony\Component\Validator\Validation;

class AllergenValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var AllergenValidator */
    private $sut;

    /**
     * @return m\MockInterface|Restfood\Entity\AllergenRepositoryInterface
     */
    private function getRepository()
    {
        return m::mock('Restfood\Entity\AllergenRepositoryInterface');
    }

    /**
     * @return \Symfony\Component\Validator\ValidatorInterface
     */
    private function getValidator()
    {
        return Validation::createValidatorBuilder()->getValidator();
    }

    /**
     * @param string $name
     * @param string $uuid
     * @param mixed $id
     * @return Allergen
     */
    private function getAllergen($name, $uuid = null, $id = null)
    {
        $allergen = new Allergen($name);
        if ($uuid || $id) {
            $reflectionClass = new \ReflectionClass('Restfood\Entity\Allergen');
        }
        if ($uuid) {
            $reflectionProperty = $reflectionClass->getProperty('uuid');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($allergen, $uuid);
        }
        if ($id) {
            $reflectionProperty = $reflectionClass->getProperty('id');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($allergen, $id);
        }
        return $allergen;
    }

    public function testValidateCreation()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new AllergenValidator($stubRepository, $validator);
        $data = ['name' => 'Foo'];

        $result = $this->sut->validateCreation($data);

        $this->assertTrue($result);
    }

    /**
     * @expectedException Restfood\Exception\InvalidDataException
     */
    public function testValidateCreationFailWhenGivenNameIsAlreadyUsed()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturn($this->getAllergen('foo'));
        $validator = $this->getValidator();
        $this->sut = new AllergenValidator($stubRepository, $validator);
        $data = ['name' => 'foo'];

        $this->sut->validateCreation($data);

        $this->fail('You should never reach this point');
    }

    /**
     * @expectedException Restfood\Exception\InvalidDataException
     */
    public function testValidateCreationFailWhenGivenNameIsEmpty()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new AllergenValidator($stubRepository, $validator);
        $data = ['name' => ''];

        $this->sut->validateCreation($data);

        $this->fail('You should never reach this point');
    }

    /**
     * @expectedException Restfood\Exception\InvalidDataException
     */
    public function testValidateCreationFailWhenGivenNameIsTooLong()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new AllergenValidator($stubRepository, $validator);
        $data = ['name' => str_repeat('a', AllergenValidator::NAME_MAX_LENGTH + 1)];

        $this->sut->validateCreation($data);

        $this->fail('You should never reach this point');
    }

    public function testValidateEdition()
    {
        $targetAllergen = $this->getAllergen('Foo', '1234');
        $data = ['id' => '1234', 'name' => 'Foo edited'];

        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new AllergenValidator($stubRepository, $validator);

        $result = $this->sut->validateEdition($targetAllergen, $data);

        $this->assertTrue($result);
    }

    public function testValidateEditionWithOptionalFieldsMissing()
    {
        $targetAllergen = $this->getAllergen('Foo', '1234');
        $data = ['name' => 'Foo edited'];

        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new AllergenValidator($stubRepository, $validator);

        $result = $this->sut->validateEdition($targetAllergen, $data);

        $this->assertTrue($result);
    }

    /**
     * @expectedException Restfood\Exception\InvalidDataException
     */
    public function testValidateEditionFailWhenImmutableFieldsHasBeenModified()
    {
        $targetAllergen = $this->getAllergen('Foo', '1234', 1);
        $data = ['id' => '5678', 'name' => 'Foo edited'];

        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new AllergenValidator($stubRepository, $validator);

        $result = $this->sut->validateEdition($targetAllergen, $data);

        $this->assertTrue($result);
    }

    /**
     * @expectedException Restfood\Exception\InvalidDataException
     */
    public function testValidateEditionFailsWhenNewNameIsAlreadyUsed()
    {
        $targetAllergen = $this->getAllergen('Foo');
        $otherAllergen = $this->getAllergen('Bar');
        $data = ['name' => 'Bar'];

        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturn($otherAllergen);
        $validator = $this->getValidator();
        $this->sut = new AllergenValidator($stubRepository, $validator);

        $this->sut->validateEdition($targetAllergen, $data);

        $this->fail('You should never reach this point');
    }
}
