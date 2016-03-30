<?php

namespace Restfood\Entity;

interface DishRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Return all the dishes that contain the given allergen.
     *
     * @param string $allergenIdentifier
     * @return Dish[]
     */
    public function findAllWithAllergen($allergenIdentifier);
}