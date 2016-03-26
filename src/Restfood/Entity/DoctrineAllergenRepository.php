<?php

namespace Restfood\Entity;

use Doctrine\ORM\EntityRepository;

class DoctrineAllergenRepository extends EntityRepository implements AllergenRepositoryInterface
{
    /** @var Allergen */
    private $allergen;

    /**
     * Persist an allergen.
     *
     * @param Allergen $allergen
     * @return Allergen
     */
    public function save(Allergen $allergen)
    {
        $this->_em->persist($allergen);
        $this->_em->flush();
        return $allergen;
    }

    /**
     * Remove the given allergen
     *
     * @param Allergen $allergen
     * @return void
     */
    public function remove(Allergen $allergen)
    {
        $this->_em->remove($allergen);
        $this->_em->flush($allergen);
    }

    /**
     * Return the allergen with the given uuid. This method is implemented in such a way that it caches the last
     * requested allergen, avoiding to perform unnecessary queries if we are requesting the same object.
     *
     * @param string $uuid
     * @return Allergen|null
     */
    public function findOneByIdentifier($uuid)
    {
        if (is_null($this->allergen) || $this->allergen->getUuid() !== $uuid) {
            $this->allergen = $this->findOneBy(array('uuid' => $uuid));
        }
        return $this->allergen;
    }

    /**
     * Update the information of the given allergen into the persistence layer.
     *
     * @param Allergen $allergen
     * @return void
     */
    public function update(Allergen $allergen)
    {
        $this->_em->flush($allergen);
    }

    /**
     * Return a list of allergens.
     *
     * @return mixed
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * Return the allergen with the given name.
     *
     * @param string $name
     * @return Allergen|null
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}