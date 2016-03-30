<?php

namespace Restfood\Manager;

use Restfood\Entity\Ingredient;

interface DishIngredientManagerInterface
{
    /**
     * Set the list of ingredients for the given dish.
     *
     * @param string $dishIdentifier
     * @param array $ingredientIds
     * @return Ingredient[]
     */
    public function setDishIngredients($dishIdentifier, array $ingredientIds);

    /**
     * Return the ingredients of the given dish.
     *
     * @param string $dishIdentifier
     * @return Ingredient[]
     */
    public function findDishIngredients($dishIdentifier);
}