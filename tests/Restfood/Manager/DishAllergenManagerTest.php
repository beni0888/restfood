<?php

namespace Restfood\Manager;

use Mockery as m;
use Restfood\Entity\AllergenRepositoryInterface;
use Restfood\Entity\DishRepositoryInterface;
use Restfood\Test\MockFactory;
use Restfood\Test\ResourceFactory;

class DishAllergenManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return DishAllergenManager
     */
    private function getSut(DishRepositoryInterface $dishRepository, AllergenRepositoryInterface $allergenRepository)
    {
        return new DishAllergenManager($dishRepository, $allergenRepository);
    }

    public function testGetAllergensInDish()
    {
        $dishRepositoryDummy = MockFactory::getDishRepository();
        $allergenRepositoryMock = MockFactory::getAllergenRepository();
        $sut = $this->getSut($dishRepositoryDummy, $allergenRepositoryMock);

        $dishIdentifier = '1234';
        $fakeResult = ResourceFactory::getAllergenList();

        $allergenRepositoryMock->shouldReceive('findAllInDish')->with($dishIdentifier)->andReturn($fakeResult);

        $this->assertEquals($fakeResult, $sut->getAllergensInDish($dishIdentifier));
    }

    public function testGetDishesWithAllergen()
    {
        $dishRepositoryMock = MockFactory::getDishRepository();
        $allergenRepositoryDummy = MockFactory::getAllergenRepository();
        $sut = $this->getSut($dishRepositoryMock, $allergenRepositoryDummy);

        $allergenIdentifier = '1234';
        $fakeResult = ResourceFactory::getDishList();

        $dishRepositoryMock->shouldReceive('findAllWithAllergen')->with($allergenIdentifier)->andReturn($fakeResult);

        $this->assertEquals($fakeResult, $sut->getDishesWithAllergen($allergenIdentifier));
    }


}