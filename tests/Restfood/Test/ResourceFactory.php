<?php

namespace Restfood\Test;

use Restfood\Entity\Allergen;
use Restfood\Entity\Dish;
use Restfood\Entity\Ingredient;

class ResourceFactory
{
    /**
     * @return Allergen[]
     */
    public static function getAllergenList()
    {
        return [new Allergen('foo'), new Allergen('bar')];
    }

    /**
     * @return Dish[]
     */
    public static function getDishList()
    {
        return [new Dish('foo'), new Dish('bar')];
    }

    /**
     * @return Ingredient[]
     */
    public static function getIngredientList()
    {
        return [new Ingredient('foo'), new Ingredient('bar')];
    }

    /**
     * @return Resource[]
     */
    public static function getResourceList()
    {
        return [self::getResource('foo'), self::getResource('bar')];
    }

    /**
     * @param $name
     * @return Dish
     */
    public static function getDish($name)
    {
        return new Dish($name);
    }

    /**
     * @param $name
     * @return Allergen
     */
    public static function getAllergen($name)
    {
        return new Allergen($name);
    }

    /**
     * @param $name
     * @return Ingredient
     */
    public static function getIngredient($name)
    {
        return new Ingredient($name);
    }

    /**
     * @param string $name
     * @param mixed $id
     * @return Resource
     */
    public static function getResource($name, $id = null)
    {
        $resource = new Resource($name);
        $resource->id = $id ?: $resource->id;
        return $resource;
    }
}