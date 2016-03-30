<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');


// ---- Parameters ----
$app['allergen.class'] = 'Restfood\Entity\Allergen';
$app['ingredient.class'] = 'Restfood\Entity\Ingredient';
$app['dish.class'] = 'Restfood\Entity\Dish';

$app['allergen.name'] = 'Allergen';
$app['ingredient.name'] = 'Ingredient';
$app['dish.name'] = 'Dish';

// ---- Services ----
$app['json.decoder'] = $app->share(function() {
    return new \Restfood\Json\JsonDecoder();
});

$app['url.generator'] = $app->share(function($app) {
    return new \Restfood\RESTFul\Url\UrlGenerator($app['url_generator']);
});

// Endpoints
$app['allergen.endpoint'] = $app->share(function($app) {
    return new \Restfood\RESTFul\Endpoint\AllergenEndpoint($app['url.generator'], $app['json.decoder'],
        $app['allergen.manager'], $app['allergen.validator']);
});

$app['ingredient.endpoint'] = $app->share(function($app) {
    return new \Restfood\RESTFul\Endpoint\IngredientEndpoint($app['url.generator'], $app['json.decoder'],
        $app['ingredient.manager'], $app['ingredient.validator']);
});

$app['dish.endpoint'] = $app->share(function($app) {
    return new \Restfood\RESTFul\Endpoint\DishEndpoint($app['url.generator'], $app['json.decoder'],
        $app['dish.manager'], $app['dish.validator']);
});

$app['dish-allergen.endpoint'] = $app->share(function($app) {
    return new \Restfood\RESTFul\Endpoint\DishAllergenEndpoint($app['dish-allergen.manager'], $app['allergen.validator'],
        $app['dish.validator']);
});

$app['dish-ingredient.endpoint'] = $app->share(function($app) {
    return new \Restfood\RESTFul\Endpoint\DishIngredientEndpoint($app['json.decoder'], $app['dish-ingredient.manager'],
        $app['dish.validator']);
});

$app['ingredient-allergen.endpoint'] = $app->share(function($app) {
    return new \Restfood\RESTFul\Endpoint\IngredientAllergenEndpoint($app['json.decoder'], $app['ingredient-allergen.manager'],
        $app['ingredient.validator']);
});

// repositories
$app['allergen.repository'] = $app->share(function($app) {
    return $app['orm.em']->getRepository('Restfood\Entity\Allergen');
});

$app['ingredient.repository'] = $app->share(function($app) {
    return $app['orm.em']->getRepository('Restfood\Entity\Ingredient');
});

$app['dish.repository'] = $app->share(function($app) {
    return $app['orm.em']->getRepository('Restfood\Entity\Dish');
});


// validators
$app['allergen.validator'] = $app->share(function($app) {
   return new \Restfood\Validation\ResourceValidator($app['allergen.name'], $app['allergen.repository'], $app['validator']);
});

$app['ingredient.validator'] = $app->share(function($app) {
    return new Restfood\Validation\ResourceValidator($app['ingredient.name'], $app['ingredient.repository'], $app['validator']);
});

$app['dish.validator'] = $app->share(function($app) {
    return new Restfood\Validation\ResourceValidator($app['dish.name'], $app['dish.repository'], $app['validator']);
});


// managers
$app['allergen.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\ResourceManager($app['allergen.class'], $app['allergen.name'],
        $app['allergen.repository'], $app['allergen.validator']);
});

$app['ingredient.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\ResourceManager($app['ingredient.class'], $app['ingredient.name'],
        $app['ingredient.repository'], $app['ingredient.validator']);
});

$app['dish.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\ResourceManager($app['dish.class'], $app['dish.name'],
        $app['dish.repository'], $app['dish.validator']);
});


$app['ingredient-allergen.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\IngredientAllergenManager($app['ingredient.repository'], $app['allergen.repository'],
        $app['ingredient.validator']);
});

$app['dish-ingredient.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\DishIngredientManager($app['dish.repository'], $app['ingredient.repository']);
});

$app['dish-allergen.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\DishAllergenManager($app['dish.repository'], $app['allergen.repository']);
});