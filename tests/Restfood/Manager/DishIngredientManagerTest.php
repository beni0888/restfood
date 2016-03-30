<?php

namespace Restfood\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Restfood\Entity\ResourceRepositoryInterface;
use Restfood\Test\MockFactory;
use Restfood\Test\ResourceFactory;

class DishIngredientManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return DishIngredientManager
     */
    private function getDishIngredientManager(ResourceRepositoryInterface $dishRepository, ResourceRepositoryInterface $ingredientRepository)
    {
        return new DishIngredientManager($dishRepository, $ingredientRepository);
    }

    public function testSetDishIngredients()
    {
        $dishRepository = MockFactory::getDishRepository();
        $ingredientRepository = MockFactory::getIngredientRepository();
        $sut = $this->getDishIngredientManager($dishRepository, $ingredientRepository);

        $dishId = '1234';
        $ingredientIds = [1, 2, 3];
        $fakeDish = ResourceFactory::getDish('foo');
        $fakeIngredients = ResourceFactory::getIngredientList();

        $dishRepository->shouldReceive('findOneByIdentifier')->with($dishId)->andReturn($fakeDish);
        $ingredientRepository->shouldReceive('findByIdentifierList')->with($ingredientIds)->andReturn($fakeIngredients);
        $dishRepository->shouldReceive('save')->with($fakeDish);

        $result = $sut->setDishIngredients($dishId, $ingredientIds);

        $this->assertEquals($fakeIngredients, $result, 'Obtained list of ingredients is not as expected');
    }

    public function testFindDishIngredients()
    {
        $dishRepository = MockFactory::getDishRepository();
        $ingredientRepository = MockFactory::getIngredientRepository();
        $sut = $this->getDishIngredientManager($dishRepository, $ingredientRepository);

        $dishId = '1234';
        $fakeIngredients = new ArrayCollection(ResourceFactory::getIngredientList());
        $dishMock = MockFactory::getDishMock('foo')->makePartial();

        $dishRepository->shouldReceive('findOneByIdentifier')->with($dishId)->andReturn($dishMock);
        $dishMock->shouldReceive('getIngredients')->andReturn($fakeIngredients);

        $expected = $fakeIngredients->toArray();
        $result = $sut->findDishIngredients($dishId);
        $this->assertEquals($expected, $result, 'Obtained list of ingredients is not as expected');
    }

}
