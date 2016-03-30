<?php

namespace Restfood\Entity;

interface AllergenRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Return the list of allergens present in the given dish.
     *
     * @param string $dishIdentifier
     * @return Allergen[]
     */
    public function findAllInDish($dishIdentifier);
}