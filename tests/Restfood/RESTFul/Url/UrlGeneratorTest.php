<?php

namespace Restfood\RESTFul\Url;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyGenerator;
use Mockery as m;

class UrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testShowAllergen()
    {
        $generatorMock = $this->getSymfonyGeneratorMock();
        $sut = $this->getUrlGenerator($generatorMock);
        
        $allergenId = '1234';
        $fakeRoute = '/foo';
        
        $generatorMock->shouldReceive('generate')
            ->with(UrlGenerator::SHOW_ALLERGEN_ROUTE_KEY, ['identifier' => $allergenId], UrlGenerator::URL_TYPE)
            ->andReturn($fakeRoute)
        ;
        
        $result = $sut->showAllergen($allergenId);
        
        $this->assertEquals($fakeRoute, $result, 'Obtained route is not as expected');
    }
    
    public function testShowIngredient()
    {
        $generatorMock = $this->getSymfonyGeneratorMock();
        $sut = $this->getUrlGenerator($generatorMock);
        
        $ingredientId = '1234';
        $fakeRoute = '/foo';
        
        $generatorMock->shouldReceive('generate')
            ->with(UrlGenerator::SHOW_INGREDIENT_ROUTE_KEY, ['identifier' => $ingredientId], UrlGenerator::URL_TYPE)
            ->andReturn($fakeRoute)
        ;
        
        $result = $sut->showIngredient($ingredientId);
        
        $this->assertEquals($fakeRoute, $result, 'Obtained route is not as expected');
    }
    
    public function testShowDish()
    {
        $generatorMock = $this->getSymfonyGeneratorMock();
        $sut = $this->getUrlGenerator($generatorMock);
        
        $dishId = '1234';
        $fakeRoute = '/foo';
        
        $generatorMock->shouldReceive('generate')
            ->with(UrlGenerator::SHOW_DISH_ROUTE_KEY, ['identifier' => $dishId], UrlGenerator::URL_TYPE)
            ->andReturn($fakeRoute)
        ;
        
        $result = $sut->showDish($dishId);
        
        $this->assertEquals($fakeRoute, $result, 'Obtained route is not as expected');
    }

    /**
     * @param SymfonyGenerator $generator
     * @return UrlGenerator
     */
    private function getUrlGenerator(SymfonyGenerator $generator)
    {
        return new UrlGenerator($generator);
    }

    /**
     * @return m\MockInterface|SymfonyGenerator
     */
    private function getSymfonyGeneratorMock()
    {
        return m::mock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
    }
    
    
}
