<?php

namespace Restfood\RESTFul\Url;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyGenerator;

class UrlGenerator implements UrlGeneratorInterface
{
    const ABSOLUTE_URL = 0;
    const URL_TYPE = self::ABSOLUTE_URL;

    const SHOW_ALLERGEN_ROUTE_KEY = 'show_allergen';
    const CREATE_ALLERGEN_ROUTE_KEY = 'create_allergen';
    const EDIT_ALLERGEN_ROUTE_KEY = 'edit_allergen';
    const DELETE_ALLERGEN_ROUTE_KEY = 'delete_allergen';
    const LIST_ALLERGENS_ROUTE_KEY = 'list_allergens';

    const SHOW_INGREDIENT_ROUTE_KEY = 'show_ingredient';
    const CREATE_INGREDIENT_ROUTE_KEY = 'create_ingredient';
    const EDIT_INGREDIENT_ROUTE_KEY = 'edit_ingredient';
    const DELETE_INGREDIENT_ROUTE_KEY = 'delete_ingredient';
    const LIST_INGREDIENTS_ROUTE_KEY = 'list_ingredients';

    const SHOW_DISH_ROUTE_KEY = 'show_dish';
    const CREATE_DISH_ROUTE_KEY = 'create_dish';
    const EDIT_DISH_ROUTE_KEY = 'edit_dish';
    const DELETE_DISH_ROUTE_KEY = 'delete_dish';
    const LIST_DISHES_ROUTE_KEY = 'list_dishes';

    const LIST_DISHES_WITH_ALLERGEN_ROUTE_KEY = 'list_dishes_with_allergen';
    const LIST_ALLERGENS_IN_DISH_ROUTE_KEY = 'list_allergens_in_dish';

    const SET_DISH_INGREDIENTS_ROUTE_KEY = 'set_dish_ingredients';
    const LIST_DISH_INGREDIENTS_ROUTE_KEY = 'list_dish_ingredients';

    const SET_INGREDIENTS_ALLERGENS_ROUTE_KEY = 'set_ingredient_allergens';
    const LIST_INGREDIENTS_ALLERGENS_ROUTE_KEY = 'list_ingredient_allergens';


    private $generator;

    /**
     * RouteGenerator constructor.
     * @param SymfonyGenerator $generator
     */
    public function __construct(SymfonyGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Return the route use to show the details of the given allergen.
     *
     * @param mixed $identifier
     * @return string
     */
    public function showAllergen($identifier)
    {
        return $this->generateUrl(self::SHOW_ALLERGEN_ROUTE_KEY, ['identifier' => $identifier]);
    }

    /**
     * Return the route use to show the details of the given ingredient.
     *
     * @param mixed $identifier
     * @return string
     */
    public function showIngredient($identifier)
    {
        return $this->generateUrl(self::SHOW_INGREDIENT_ROUTE_KEY, ['identifier' => $identifier]);
    }

    /**
     * Return the route use to show the details of the given dish.
     *
     * @param mixed $identifier
     * @return string
     */
    public function showDish($identifier)
    {
        return $this->generateUrl(self::SHOW_DISH_ROUTE_KEY, ['identifier' => $identifier]);
    }

    /**
     * Generate a url from the route with given key and params.
     *
     * @param string $routeKey
     * @param array $routeParams
     * @return string
     */
    private function generateUrl($routeKey, $routeParams = [])
    {
        return $this->generator->generate($routeKey, $routeParams, self::URL_TYPE);
    }
}