<?php

namespace Restfood\Validation;

use Mockery as m;
use Ramsey\Uuid\Uuid;
use Restfood\Entity\ResourceInterface;
use Symfony\Component\Validator\Validation;

class ResourceValidatorTest extends \PHPUnit_Framework_TestCase
{
    const RESOURCE_NAME = 'Resource';

    /** @var ResourceValidator */
    private $sut;

    /**
     * @return m\MockInterface|\Restfood\Entity\ResourceRepositoryInterface
     */
    private function getRepository()
    {
        return m::mock('\Restfood\Entity\ResourceRepositoryInterface');
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
     * @param mixed $id
     * @return Resource
     */
    private function getResource($name, $id = null)
    {
        $resource = new Resource($name);
        $resource->id = $id ?: $resource->id;
        return $resource;
    }

    public function testAssertExists()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByIdentifier')->andReturn($this->getResource('foo'));
        $validatorDummy = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validatorDummy);
        $resourceId = '123';

        $this->assertTrue($this->sut->assertExists($resourceId));
    }

    /**
     * @expectedException \Restfood\Exception\ResourceNotFoundException
     */
    public function testAssertExistsFails()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByIdentifier')->andReturnNull();
        $validatorDummy = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validatorDummy);
        $resourceId = '123';

        $this->sut->assertExists($resourceId);

        $this->fail('You should never reach this point');
    }

    public function testValidateCreation()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validator);
        $data = ['name' => 'Foo'];

        $result = $this->sut->validateCreation($data);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Restfood\Exception\InvalidDataException
     */
    public function testValidateCreationFailWhenGivenNameIsAlreadyUsed()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturn($this->getResource('foo'));
        $validator = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validator);
        $data = ['name' => 'foo'];

        $this->sut->validateCreation($data);

        $this->fail('You should never reach this point');
    }

    /**
     * @expectedException \Restfood\Exception\InvalidDataException
     */
    public function testValidateCreationFailWhenGivenNameIsEmpty()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validator);
        $data = ['name' => ''];

        $this->sut->validateCreation($data);

        $this->fail('You should never reach this point');
    }

    /**
     * @expectedException \Restfood\Exception\InvalidDataException
     */
    public function testValidateCreationFailWhenGivenNameIsTooLong()
    {
        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validator);
        $data = ['name' => str_repeat('a', ResourceValidator::NAME_MAX_LENGTH + 1)];

        $this->sut->validateCreation($data);

        $this->fail('You should never reach this point');
    }

    public function testValidateEdition()
    {
        $target = $this->getResource('Foo', '1234');
        $data = ['id' => '1234', 'name' => 'Foo edited'];

        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validator);

        $result = $this->sut->validateEdition($target, $data);

        $this->assertTrue($result);
    }

    public function testValidateEditionWithOptionalFieldsMissing()
    {
        $target = $this->getResource('Foo', '1234');
        $data = ['name' => 'Foo edited'];

        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validator);

        $result = $this->sut->validateEdition($target, $data);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Restfood\Exception\InvalidDataException
     */
    public function testValidateEditionFailWhenImmutableFieldsHasBeenModified()
    {
        $target = $this->getResource('Foo', '1234', 1);
        $data = ['id' => '5678', 'name' => 'Foo edited'];

        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturnNull();
        $validator = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validator);

        $result = $this->sut->validateEdition($target, $data);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Restfood\Exception\InvalidDataException
     */
    public function testValidateEditionFailsWhenNewNameIsAlreadyUsed()
    {
        $target = $this->getResource('Foo');
        $existentResource = $this->getResource('Bar');
        $data = ['name' => 'Bar'];

        $stubRepository = $this->getRepository();
        $stubRepository->shouldReceive('findOneByName')->andReturn($existentResource);
        $validator = $this->getValidator();
        $this->sut = new ResourceValidator(self::RESOURCE_NAME, $stubRepository, $validator);

        $this->sut->validateEdition($target, $data);

        $this->fail('You should never reach this point');
    }
}

class Resource implements ResourceInterface {

    public $id;
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
        $this->id = Uuid::uuid4()->toString();
    }

    public static function obtainIdentifierFieldName()
    {
        return 'id';
    }

    public function obtainIdentifier()
    {
        return $this->id;
    }

    public function obtainName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}