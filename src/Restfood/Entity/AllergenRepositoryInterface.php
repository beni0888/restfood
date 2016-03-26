<?php

namespace Restfood\Entity;

interface AllergenRepositoryInterface
{
    /**
     * Persist an allergen.
     *
     * @param Allergen $allergen
     * @return Allergen
     */
    public function save(Allergen $allergen);

    /**
     * Remove the given allergen
     *
     * @param Allergen $allergen
     * @return void
     */
    public function remove(Allergen $allergen);

    /**
     * Return the allergen with the given uuid.
     *
     * @param string $uuid
     * @return Allergen|null
     */
    public function findOneByIdentifier($uuid);

    /**
     * Return the allergen with the given name.
     *
     * @param string $name
     * @return Allergen|null
     */
    public function findOneByName($name);

    /**
     * Update the information of the given allergen into the persistence layer.
     *
     * @param Allergen $allergen
     * @return void
     */
    public function update(Allergen $allergen);

    /**
     * Return a list of allergens.
     *
     * @return mixed
     */
    public function findAll();
}