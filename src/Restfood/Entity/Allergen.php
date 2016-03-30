<?php

namespace Restfood\Entity;
use Ramsey\Uuid\Uuid;

/**
 * Allergen
 */
class Allergen implements \JsonSerializable, ResourceInterface
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
    private $ingredients;

    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $uuid = Uuid::uuid4();
        $this->name = $name;
        $this->uuid = $uuid->toString();
        $this->ingredients = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Allergen
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
     * Add ingredient
     *
     * @param \Restfood\Entity\Ingredient $ingredient
     *
     * @return Allergen
     */
    public function addIngredient(\Restfood\Entity\Ingredient $ingredient)
    {
        $this->ingredients[] = $ingredient;

        return $this;
    }

    /**
     * Remove ingredient
     *
     * @param \Restfood\Entity\Ingredient $ingredient
     */
    public function removeIngredient(\Restfood\Entity\Ingredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);
    }

    /**
     * Get ingredients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Return the value of the field used as identifier of the resource.
     *
     * @return string
     */
    public function obtainIdentifier()
    {
        return $this->getUuid();
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
            'id' => $this->obtainIdentifier(),
            'name' => $this->name,
        ];
    }

    /**
     * Return the name of the field that is used as the resource identifier.
     *
     * @return string
     */
    public static function obtainIdentifierFieldName()
    {
        return 'uuid';
    }

    /**
     * Return the resource name.
     *
     * @return string
     */
    public function obtainName()
    {
        return $this->getName();
    }
}

