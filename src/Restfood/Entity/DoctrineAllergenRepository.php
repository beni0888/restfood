<?php

namespace Restfood\Entity;

class DoctrineAllergenRepository extends DoctrineResourceRepository implements AllergenRepositoryInterface
{
    const DISH_CLASS = 'Restfood\Entity\Dish';

    /**
     * Return the list of allergen of the given dish.
     *
     * @param string $dishIdentifier
     * @return Allergen[]
     */
    public function findAllInDish($dishIdentifier)
    {
        $dishIdentifierField = $this->obtainDishIdentifierField();

        return $this->createQueryBuilder('a')
            ->join('a.ingredients', 'i')
            ->join('i.dishes', 'd')
            ->where("d.$dishIdentifierField = :dish_identifier")
            ->setParameter('dish_identifier', $dishIdentifier)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return the name of the field used as identifier of the dish resource.
     *
     * @return string
     */
    private function obtainDishIdentifierField()
    {
        $dishClass = self::DISH_CLASS;
        return $dishClass::obtainIdentifierFieldName();
    }
}