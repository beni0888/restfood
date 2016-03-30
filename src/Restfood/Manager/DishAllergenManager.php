<?php

namespace Restfood\Manager;

use Restfood\Entity\AllergenRepositoryInterface;
use Restfood\Entity\Allergen;
use Restfood\Entity\Dish;
use Restfood\Entity\DishRepositoryInterface;

class DishAllergenManager
{
    private $dishRepository;
    private $allergenRepository;

    /**
     * DishAllergenManager constructor.
     *
     * @param DishRepositoryInterface $dishRepository
     * @param AllergenRepositoryInterface $allergenRepository
     */
    public function __construct(
        DishRepositoryInterface $dishRepository,
        AllergenRepositoryInterface $allergenRepository
    ) {
        $this->dishRepository = $dishRepository;
        $this->allergenRepository = $allergenRepository;
    }

    /**
     * Return the allergens present in a given dish.
     *
     * @param string $dishIdentifier
     * @return Allergen[]
     */
    public function getAllergensInDish($dishIdentifier)
    {
        $allergens = $this->allergenRepository->findAllInDish($dishIdentifier);
        return $allergens;
    }

    /**
     * Return the dishes that contain the given allergen.
     *
     * @param string $allergenIdentifier
     * @return Dish[]
     */
    public function getDishesWithAllergen($allergenIdentifier)
    {
        $dishes = $this->dishRepository->findAllWithAllergen($allergenIdentifier);
        return $dishes;
    }
}