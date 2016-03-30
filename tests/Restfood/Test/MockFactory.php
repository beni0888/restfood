<?php

namespace Restfood\Test;

use Mockery as m;
use Restfood\Entity\Allergen;
use Restfood\Entity\Dish;
use Restfood\Entity\Ingredient;
use Restfood\Json\JsonDecoder;
use Restfood\Manager\DishAllergenManager;
use Restfood\Manager\DishIngredientManagerInterface;
use Restfood\Manager\IngredientAllergenManagerInterface;
use Restfood\Manager\ResourceManagerInterface;
use Restfood\RESTFul\Url\UrlGeneratorInterface;

class MockFactory
{
    /**
     * @return m\MockInterface|JsonDecoder
     */
    public static function getJsonDecoder()
    {
        return m::mock('Restfood\Json\JsonDecoder');
    }

    /**
     * @return m\MockInterface|UrlGeneratorInterface
     */
    public static function getUrlGenerator()
    {
        return m::mock('Restfood\RESTFul\Url\UrlGeneratorInterface');
    }

    /**
     * @return m\MockInterface|ResourceManagerInterface
     */
    public static function getResourceManager()
    {
        return m::mock('Restfood\Manager\ResourceManagerInterface');
    }

    /**
     * @return m\MockInterface|IngredientAllergenManagerInterface
     */
    public static function getIngredientAllergenManager()
    {
        return m::mock('Restfood\Manager\IngredientAllergenManagerInterface');
    }

    /**
     * @return m\MockInterface|DishIngredientManagerInterface
     */
    public static function getDishIngredientManager()
    {
        return m::mock('Restfood\Manager\DishIngredientManagerInterface');
    }

    /**
     * @return m\MockInterface|DishAllergenManager
     */
    public static function getDishAllergenManager()
    {
        return m::mock('Restfood\Manager\DishAllergenManager');
    }

    /**
     * @return m\MockInterface|ResourceManagerInterface
     */
    public static function getAllergenManager()
    {
        return m::mock('Restfood\Manager\ResourceManagerInterface');
    }

    /**
     * @return m\MockInterface|ResourceManagerInterface
     */
    public static function getIngredientManager()
    {
        return m::mock('Restfood\Manager\ResourceManagerInterface');
    }

    /**
     * @return m\MockInterface|ResourceManagerInterface
     */
    public static function getDishManager()
    {
        return m::mock('Restfood\Manager\ResourceManagerInterface');
    }

    /**
     * @return m\MockInterface|\Restfood\Entity\ResourceRepositoryInterface
     */
    public static function getResourceRepository()
    {
        return m::mock('Restfood\Entity\ResourceRepositoryInterface');
    }

    /**
     * @return m\MockInterface|\Restfood\Entity\ResourceRepositoryInterface
     */
    public static function getIngredientRepository()
    {
        return m::mock('Restfood\Entity\ResourceRepositoryInterface');
    }

    /**
     * @return m\MockInterface|\Restfood\Entity\DishRepositoryInterface
     */
    public static function getDishRepository()
    {
        return m::mock('Restfood\Entity\DishRepositoryInterface');
    }

    /**
     * @return m\MockInterface|\Restfood\Entity\AllergenRepositoryInterface
     */
    public static function getAllergenRepository()
    {
        return m::mock('Restfood\Entity\AllergenRepositoryInterface');
    }

    /**
     * @return m\MockInterface|\Restfood\Validation\ResourceValidatorInterface
     */
    public static function getResourceValidator()
    {
        return m::mock('Restfood\Validation\ResourceValidatorInterface');
    }

    /**
     * @return m\MockInterface|\Restfood\Validation\ResourceValidatorInterface
     */
    public static function getDishValidator()
    {
        return m::mock('Restfood\Validation\ResourceValidatorInterface');
    }

    /**
     * @return m\MockInterface|\Restfood\Validation\ResourceValidatorInterface
     */
    public static function getAllergenValidator()
    {
        return m::mock('Restfood\Validation\ResourceValidatorInterface');
    }

    /**
     * @return m\MockInterface|\Restfood\Validation\ResourceValidatorInterface
     */
    public static function getIngredientValidator()
    {
        return m::mock('Restfood\Validation\ResourceValidatorInterface');
    }

    /**
     * @param $name
     * @return m\MockInterface|Dish
     */
    public static function getDishMock($name)
    {
        return m::mock('Restfood\Entity\Dish', array($name));
    }

    /**
     * @param $name
     * @return m\MockInterface|Ingredient
     */
    public static function getIngredientMock($name)
    {
        return m::mock('Restfood\Entity\Ingredient', array($name));
    }

    /**
     * @param $name
     * @return m\MockInterface|Allergen
     */
    public static function getAllergenMock($name)
    {
        return m::mock('Restfood\Entity\Allergen', array($name));
    }
}