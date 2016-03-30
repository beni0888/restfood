<?php

namespace Restfood\Entity;

class DoctrineDishRepository extends DoctrineResourceRepository implements DishRepositoryInterface
{
    const ALLERGEN_CLASS = 'Restfood\Entity\Allergen';

    /**
     * Return all the dishes that contain the given allergen.
     *
     * @param string $allergenIdentifier
     * @return Dish[]
     */
    public function findAllWithAllergen($allergenIdentifier)
    {
        $allergenIdentifierField = $this->obtainAllergenIdentifierField();

        return $this->createQueryBuilder('d')
            ->join('d.ingredients', 'i')
            ->join('i.allergens', 'a')
            ->where("a.$allergenIdentifierField = :allergen_identifier")
            ->setParameter('allergen_identifier', $allergenIdentifier)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return the name of the field used as identifier of the dish resource.
     *
     * @return string
     */
    private function obtainAllergenIdentifierField()
    {
        $dishClass = self::ALLERGEN_CLASS;
        return $dishClass::obtainIdentifierFieldName();
    }
}