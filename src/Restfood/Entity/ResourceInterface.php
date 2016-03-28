<?php

namespace Restfood\Entity;


interface ResourceInterface
{
    /**
     * Return the name of the field that is used as the resource identifier.
     *
     * @return string
     */
    public static function obtainIdentifierFieldName();

    /**
     * Return the resource identifier value.
     *
     * @return mixed
     */
    public function obtainIdentifier();

    /**
     * Return the resource name.
     *
     * @return string
     */
    public function obtainName();

    /**
     * Set the resource name.
     *
     * @param string $name
     * @return mixed
     */
    public function setName($name);
}