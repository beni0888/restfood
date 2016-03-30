<?php

namespace Restfood\Manager;

use Mockery as m;
use Restfood\Entity\ResourceRepositoryInterface;
use Restfood\Test\MockFactory;
use Restfood\Test\Resource;
use Restfood\Test\ResourceFactory;
use Restfood\Validation\ResourceValidatorInterface;

class ResourceManagerTest extends \PHPUnit_Framework_TestCase
{
    const RESOURCE_CLASS = Resource::class;
    const RESOURCE_NAME = 'Resource';

    /**
     * @return ResourceManager
     */
    public function getResourceManager($resourceClass, $resourceName, ResourceRepositoryInterface $repository,
        ResourceValidatorInterface $validator)
    {
        return new ResourceManager($resourceClass, $resourceName, $repository, $validator);
    }

    public function testCreate()
    {
        $repositoryStub = MockFactory::getResourceRepository();
        $validatorStub = MockFactory::getResourceValidator();
        $sut = $this->getResourceManager(self::RESOURCE_CLASS, self::RESOURCE_NAME, $repositoryStub, $validatorStub);

        $data = ['name' => 'foo'];
        $fakeResource = ResourceFactory::getResource('foo');

        $validatorStub->shouldReceive('validateCreation');
        $repositoryStub->shouldReceive('save')->andReturn($fakeResource);

        $result = $sut->create($data);
        $expected = $fakeResource;

        $this->assertEquals($expected, $result, 'Obtained resource does not match the expected one');
    }

    public function testFindOne()
    {
        $repositoryStub = MockFactory::getResourceRepository();
        $validatorDummy = MockFactory::getResourceValidator();
        $sut = $this->getResourceManager(self::RESOURCE_CLASS, self::RESOURCE_NAME, $repositoryStub, $validatorDummy);

        $resourceId = '1234';
        $fakeResource = ResourceFactory::getResource('foo');

        $repositoryStub->shouldReceive('findOneByIdentifier')->with($resourceId)->andReturn($fakeResource);

        $result = $sut->findOne($resourceId);
        $expected = $fakeResource;

        $this->assertEquals($expected, $result, 'Obtained resource does not match the expected one');
    }

    public function testFindAll()
    {
        $repositoryStub = MockFactory::getResourceRepository();
        $validatorDummy = MockFactory::getResourceValidator();
        $sut = $this->getResourceManager(self::RESOURCE_CLASS, self::RESOURCE_NAME, $repositoryStub, $validatorDummy);

        $fakeResources = ResourceFactory::getResourceList();

        $repositoryStub->shouldReceive('findAll')->andReturn($fakeResources);

        $result = $sut->findAll();
        $expected = $fakeResources;

        $this->assertEquals($expected, $result, 'Obtained list of resources does not match the expected one');
    }

    public function testEdit()
    {
        $repositoryStub = MockFactory::getResourceRepository();
        $validatorStub = MockFactory::getResourceValidator();
        $sut = $this->getResourceManager(self::RESOURCE_CLASS, self::RESOURCE_NAME, $repositoryStub, $validatorStub);

        $resourceId = '1234';
        $data = ['name' => 'bar'];
        $fakeResource = ResourceFactory::getResource('foo');
        $expected = $fakeResource;
        $expected->setName('bar');

        $repositoryStub->shouldReceive('findOneByIdentifier')->with($resourceId)->andReturn($fakeResource);
        $validatorStub->shouldReceive('validateEdition')->with($fakeResource, $data);
        $repositoryStub->shouldReceive('update')->with($fakeResource);

        $result = $sut->edit($resourceId, $data);

        $this->assertEquals($expected, $result, 'Obtained resource does not match the expected one');
    }

    public function testRemove()
    {
        $repositoryStub = MockFactory::getResourceRepository();
        $validatorDummy = MockFactory::getResourceValidator();
        $sut = $this->getResourceManager(self::RESOURCE_CLASS, self::RESOURCE_NAME, $repositoryStub, $validatorDummy);

        $resourceId = '1234';
        $fakeResource = ResourceFactory::getResource('foo');

        $repositoryStub->shouldReceive('findOneByIdentifier')->with($resourceId)->andReturn($fakeResource);
        $repositoryStub->shouldReceive('remove')->with($fakeResource);

        $sut->remove($resourceId);
    }


}
