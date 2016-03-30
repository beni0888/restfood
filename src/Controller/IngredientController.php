<?php

use Restfood\RESTFul\Url\UrlGenerator;

$app->post('/ingredients', function() use ($app) {
    $data = file_get_contents('php://input');
    return $app['ingredient.endpoint']->createResource($data);
})
->bind(UrlGenerator::CREATE_INGREDIENT_ROUTE_KEY);


$app->get('/ingredients/{identifier}', function($identifier) use ($app) {
    return $app['ingredient.endpoint']->showResource($identifier);
})
->bind(UrlGenerator::SHOW_INGREDIENT_ROUTE_KEY);


$app->get('/ingredients', function() use ($app) {
    return $app['ingredient.endpoint']->listResources();
})
->bind(UrlGenerator::LIST_INGREDIENTS_ROUTE_KEY);


$app->delete('/ingredients/{identifier}', function($identifier) use ($app) {
    return $app['ingredient.endpoint']->deleteResource($identifier);
})
->bind(UrlGenerator::DELETE_INGREDIENT_ROUTE_KEY);


$app->put('/ingredients/{identifier}', function($identifier) use ($app) {
    $data = file_get_contents('php://input');
    return $app['ingredient.endpoint']->editResource($identifier, $data);
})
->bind(UrlGenerator::EDIT_INGREDIENT_ROUTE_KEY);


$app->put('/ingredients/{identifier}/allergens', function($identifier) use ($app) {
    $data = file_get_contents('php://input');
    return $app['ingredient-allergen.endpoint']->setIngredientAllergens($identifier, $data);
})
->bind(UrlGenerator::SET_INGREDIENTS_ALLERGENS_ROUTE_KEY);


$app->get('/ingredients/{identifier}/allergens', function($identifier) use ($app) {
    return $app['ingredient-allergen.endpoint']->listIngredientAllergens($identifier);
})
->bind(UrlGenerator::LIST_INGREDIENTS_ALLERGENS_ROUTE_KEY);