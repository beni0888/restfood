<?php

namespace Restfood\Manager;

use Restfood\Entity\Allergen;
use Restfood\Entity\Ingredient;
use Restfood\Entity\ResourceRepositoryInterface;

class IngredientAllergenManager implements IngredientAllergenManagerInterface
{
    private $ingredientRepository;
    private $allergenRepository;

    /**
     * IngredientAllergenManager constructor.
     * @param ResourceRepositoryInterface $ingredientRepository
     * @param ResourceRepositoryInterface $allergenRepository
     */
    public function __construct(
        ResourceRepositoryInterface $ingredientRepository,
        ResourceRepositoryInterface $allergenRepository
    ) {
        $this->ingredientRepository = $ingredientRepository;
        $this->allergenRepository = $allergenRepository;
    }

    /**
     * Set the list of allergens for the given ingredient.
     *
     * @param string $ingredientIdentifier
     * @param array $allergenIds
     * @return Allergen[]
     */
    public function setIngredientAllergens($ingredientIdentifier, array $allergenIds)
    {
        $ingredient = $this->ingredientRepository->findOneByIdentifier($ingredientIdentifier);
        $allergens = $this->allergenRepository->findByIdentifierList($allergenIds);

        $this->removeAllergens($ingredient);
        $this->setAllergens($ingredient, $allergens);

        $this->ingredientRepository->save($ingredient);

        return $allergens;
    }

    /**
     * Return the allergens of the given ingredient.
     *
     * @param string $ingredientIdentifier
     * @return Allergen[]
     */
    public function findIngredientAllergens($ingredientIdentifier)
    {
        $ingredient = $this->ingredientRepository->findOneByIdentifier($ingredientIdentifier);
        $allergens = $ingredient->getAllergens();

        return $allergens->toArray();
    }

    /**
     * Remove all the allergens from the given ingredient.
     *
     * @param Ingredient $ingredient
     * @return void
     */
    private function removeAllergens(Ingredient $ingredient)
    {
        $allergens = $ingredient->getAllergens();
        foreach ($allergens as $allergen) {
            $ingredient->removeAllergen($allergen);
        }
    }

    /**
     * Set the allergens for the given ingredient.
     *
     * @param Ingredient $ingredient
     * @param array $allergens
     * @return void
     */
    private function setAllergens(Ingredient $ingredient, array $allergens)
    {
        foreach ($allergens as $allergen) {
            $ingredient->addAllergen($allergen);
        }
    }
}