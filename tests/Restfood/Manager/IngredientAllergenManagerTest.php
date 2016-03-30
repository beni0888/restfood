<?php

namespace Restfood\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Restfood\Entity\ResourceRepositoryInterface;
use Restfood\Test\MockFactory;
use Restfood\Test\ResourceFactory;

class IngredientAllergenManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return IngredientAllergenManager
     */
    public function getIngredientAllergenManager(
        ResourceRepositoryInterface $ingredientRepository,
        ResourceRepositoryInterface $allergenRepository
    ) {
        return new IngredientAllergenManager($ingredientRepository, $allergenRepository);
    }

    public function testSetIngredientAllergens()
    {
        $ingredientRepository = MockFactory::getIngredientRepository();
        $allergenRepository = MockFactory::getAllergenRepository();
        $sut = $this->getIngredientAllergenManager($ingredientRepository, $allergenRepository);

        $ingredientId = '1234';
        $allergenIds = [1, 2, 3];
        $fakeIngredient = ResourceFactory::getIngredient('foo');
        $fakeAllergens = ResourceFactory::getAllergenList();

        $ingredientRepository->shouldReceive('findOneByIdentifier')->with($ingredientId)->andReturn($fakeIngredient);
        $allergenRepository->shouldReceive('findByIdentifierList')->with($allergenIds)->andReturn($fakeAllergens);
        $ingredientRepository->shouldReceive('save')->with($fakeIngredient);

        $expected = $fakeAllergens;
        $result = $sut->setIngredientAllergens($ingredientId, $allergenIds);
        $this->assertEquals($expected, $result, 'The obtained list of allergens is not as expected');
    }

    public function testFindIngredientAllergens()
    {
        $ingredientRepository = MockFactory::getIngredientRepository();
        $allergenRepository = MockFactory::getAllergenRepository();
        $sut = $this->getIngredientAllergenManager($ingredientRepository, $allergenRepository);

        $ingredientId = '1234';
        $ingredientMock = MockFactory::getIngredientMock('foo');
        $fakeAllergens = new ArrayCollection(ResourceFactory::getAllergenList());

        $ingredientRepository->shouldReceive('findOneByIdentifier')->with($ingredientId)->andReturn($ingredientMock);
        $ingredientMock->shouldReceive('getAllergens')->andReturn($fakeAllergens);

        $expected = $fakeAllergens->toArray();
        $result = $sut->findIngredientAllergens($ingredientId);
        $this->assertEquals($expected, $result, 'The obtained list of allergens is not as expected');
    }
}
