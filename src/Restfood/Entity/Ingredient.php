<?php

namespace Restfood\Entity;
use Ramsey\Uuid\Uuid;

/**
 * Ingredient
 */
class Ingredient implements \JsonSerializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $allergens;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $dishes;

    /**
     * Constructor
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->uuid = Uuid::uuid4()->toString();
        $this->allergens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dishes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Ingredient
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add allergen
     *
     * @param \Restfood\Entity\Allergen $allergen
     *
     * @return Ingredient
     */
    public function addAllergen(\Restfood\Entity\Allergen $allergen)
    {
        $allergen->addIngredient($this);
        $this->allergens[] = $allergen;

        return $this;
    }

    /**
     * Remove allergen
     *
     * @param \Restfood\Entity\Allergen $allergen
     */
    public function removeAllergen(\Restfood\Entity\Allergen $allergen)
    {
        $this->allergens->removeElement($allergen);
    }

    /**
     * Get allergens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllergens()
    {
        return $this->allergens;
    }

    /**
     * Add dish
     *
     * @param \Restfood\Entity\Dish $dish
     *
     * @return Ingredient
     */
    public function addDish(\Restfood\Entity\Dish $dish)
    {
        $this->dishes[] = $dish;

        return $this;
    }

    /**
     * Remove dish
     *
     * @param \Restfood\Entity\Dish $dish
     */
    public function removeDish(\Restfood\Entity\Dish $dish)
    {
        $this->dishes->removeElement($dish);
    }

    /**
     * Get dishes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDishes()
    {
        return $this->dishes;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];
    }
}

