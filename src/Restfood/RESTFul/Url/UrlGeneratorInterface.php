<?php

namespace Restfood\RESTFul\Url;

interface UrlGeneratorInterface
{
    /**
     * Return the route use to show the details of the given allergen.
     *
     * @param mixed $identifier
     * @return string
     */
    public function showAllergen($identifier);

    /**
     * Return the route use to show the details of the given ingredient.
     *
     * @param mixed $identifier
     * @return string
     */
    public function showIngredient($identifier);

    /**
     * Return the route use to show the details of the given dish.
     *
     * @param mixed $identifier
     * @return string
     */
    public function showDish($identifier);
}