<?php

namespace Restfood\Manager;

use Restfood\Entity\Allergen;

interface IngredientAllergenManagerInterface
{
    /**
     * Set the list of allergens for the given ingredient.
     *
     * @param string $ingredientIdentifier
     * @param array $allergenIds
     * @return Allergen[]
     */
    public function setIngredientAllergens($ingredientIdentifier, array $allergenIds);

    /**
     * Return the allergens of the given ingredient.
     *
     * @param string $ingredientIdentifier
     * @return Allergen[]
     */
    public function findIngredientAllergens($ingredientIdentifier);
}