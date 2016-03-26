<?php

namespace Restfood\Manager;

use Restfood\Entity\Allergen;
use Mockery as m;
use Restfood\Entity\AllergenRepositoryInterface;
use Restfood\Validation\AllergenValidator;

class AllergenManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Restfood\Manager\AllergenManager */
    private $sut;

    /**
     * @expectedException Restfood\Exception\InvalidDataException
     */
    public function testCreateFailsWithInvalidJsonData()
    {
        $repositoryDummy = $this->getRepository();
        $validatorDummy = $this->getValidator();
        $this->sut = new AllergenManager($repositoryDummy, $validatorDummy);
        $data = null;

        $this->sut->create($data);
        $this->fail('You should never reach this point');
    }

    public function testCreate()
    {
        $fakeAllergen = new Allergen('foo', 'bar');

        $repositoryStub = $this->getRepository();
        $repositoryStub->shouldReceive('save')->andReturn($fakeAllergen);
        $validatorStub = $this->getValidator();
        $validatorStub->shouldReceive('validateCreation')->andReturn(true);
        $this->sut = new AllergenManager($repositoryStub, $validatorStub);

        $result = $this->sut->create('{"name":"foo", "slug":"bar"}');

        $this->assertEquals($fakeAllergen, $result, 'Obtained value is not the expected one');
    }

    public function testShow()
    {
        $fakeAllergen = new Allergen('foo', 'bar');

        $repositoryStub = $this->getRepository();
        $repositoryStub->shouldReceive('findOneByIdentifier')->andReturn($fakeAllergen);
        $validatorDummy = $this->getValidator();
        $this->sut = new AllergenManager($repositoryStub, $validatorDummy);
        $id = '1234';

        $result = $this->sut->show($id);

        $this->assertEquals($fakeAllergen, $result, 'Obtained value is not the expected one');
    }

    /**
     * @expectedException Restfood\Exception\ResourceNotFoundException
     */
    public function testShowFailsIfTheResourceIsNotFound()
    {
        $repositoryStub = $this->getRepository();
        $repositoryStub->shouldReceive('findOneByIdentifier')->andReturnNull();
        $validatorDummy = $this->getValidator();
        $this->sut = new AllergenManager($repositoryStub, $validatorDummy);
        $id = '1234';

        $this->sut->show($id);

        $this->fail('You should never reach this point');
    }

    public function testShowAll()
    {
        $fakeAllergenList = array(
            new Allergen('Foo', 'foo'),
            new Allergen('Bar', 'bar'),
        );

        $repositoryStub = $this->getRepository();
        $repositoryStub->shouldReceive('findAll')->andReturn($fakeAllergenList);
        $validatorDummy = $this->getValidator();
        $this->sut = new AllergenManager($repositoryStub, $validatorDummy);

        $result = $this->sut->showList();

        $this->assertEquals($fakeAllergenList, $result);
    }

    /**
     * @expectedException Restfood\Exception\InvalidDataException
     */
    public function testEditFailsWithInvalidJsonData()
    {
        $repositoryDummy = $this->getRepository();
        $validatorDummy = $this->getValidator();
        $this->sut = new AllergenManager($repositoryDummy, $validatorDummy);

        $this->sut->edit($id = '1234', $data = null);
        $this->fail('You should never reach this point');
    }

    public function testEdit()
    {
        $fakeAllergen = $modifiedFakeAllergen = new Allergen('foo');
        $modifiedFakeAllergen->setName('foo modified');

        $repositoryStub = $this->getRepository();
        $repositoryStub->shouldReceive('findOneByIdentifier')->andReturn($fakeAllergen);
        $repositoryStub->shouldReceive('update');
        $validatorStub = $this->getValidator();
        $validatorStub->shouldReceive('validateEdition')->andReturn(true);
        $this->sut = new AllergenManager($repositoryStub, $validatorStub);

        $result = $this->sut->edit($id = 1234, $data = '{"name":"foo modified"}');

        $this->assertEquals($modifiedFakeAllergen, $result, 'Obtained value is not the expected one');
    }

    /**
     * @expectedException Restfood\Exception\ResourceNotFoundException
     */
    public function testEditFailsWhenTheResourceDoesNotExist()
    {
        $repositoryStub = $this->getRepository();
        $repositoryStub->shouldReceive('findOneByIdentifier')->andReturnNull();
        $validatorDummy = $this->getValidator();
        $this->sut = new AllergenManager($repositoryStub, $validatorDummy);

        $this->sut->edit($id = 1234, $data = '{"name":"foo modified", "slug":"bar"}');

        $this->fail('You should never reach this point');
    }

    /**
     * Return a repository test double.
     *
     * @return m\MockInterface|AllergenRepositoryInterface
     */
    private function getRepository()
    {
        return m::mock('Restfood\Entity\AllergenRepositoryInterface');
    }

    /**
     * Return a validator test double.
     *
     * @return m\MockInterface|AllergenValidator
     */
    private function getValidator()
    {
        return m::mock('Restfood\Validation\AllergenValidator');
    }



}
