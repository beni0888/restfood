<?php

namespace Restfood\Manager;

use Restfood\Entity\Dish;
use Restfood\Entity\Ingredient;
use Restfood\Entity\ResourceRepositoryInterface;

class DishIngredientManager implements DishIngredientManagerInterface
{
    private $dishRepository;
    private $ingredientRepository;

    /**
     * IngredientAllergenManager constructor.
     * @param ResourceRepositoryInterface $dishRepository
     * @param ResourceRepositoryInterface $ingredientRepository
     */
    public function __construct(
        ResourceRepositoryInterface $dishRepository,
        ResourceRepositoryInterface $ingredientRepository
    ) {
        $this->dishRepository = $dishRepository;
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * Set the list of ingredients for the given dish.
     *
     * @param string $dishIdentifier
     * @param array $ingredientIds
     * @return Ingredient[]
     */
    public function setDishIngredients($dishIdentifier, array $ingredientIds)
    {
        $dish = $this->dishRepository->findOneByIdentifier($dishIdentifier);
        $ingredients = $this->ingredientRepository->findByIdentifierList($ingredientIds);

        $this->removeIngredients($dish);
        $this->setIngredients($dish, $ingredients);

        $this->dishRepository->save($dish);

        return $ingredients;
    }

    /**
     * Return the ingredients of the given dish.
     *
     * @param string $dishIdentifier
     * @return Ingredient[]
     */
    public function findDishIngredients($dishIdentifier)
    {
        $dish = $this->dishRepository->findOneByIdentifier($dishIdentifier);
        $ingredients = $dish->getIngredients();

        return $ingredients->toArray();
    }

    /**
     * Remove all the allergens from the given ingredient.
     *
     * @param Dish $dish
     * @return void
     */
    private function removeIngredients(Dish $dish)
    {
        $ingredients = $dish->getIngredients();
        foreach ($ingredients as $ingredient) {
            $dish->removeIngredient($ingredient);
        }
    }

    /**
     * Set the allergens for the given ingredient.
     *
     * @param Dish $dish
     * @param array $ingredients
     * @return void
     */
    private function setIngredients(Dish $dish, array $ingredients)
    {
        foreach ($ingredients as $ingredient) {
            $dish->addIngredient($ingredient);
        }
    }
}