<?php

use Restfood\RESTFul\Url\UrlGenerator;

$app->post('/dishes', function() use ($app) {
    $data = file_get_contents('php://input');
    return $app['dish.endpoint']->createResource($data);
})
->bind(UrlGenerator::CREATE_DISH_ROUTE_KEY);


$app->get('/dishes/{identifier}', function($identifier) use ($app) {
    return $app['dish.endpoint']->showResource($identifier);
})
->bind(UrlGenerator::SHOW_DISH_ROUTE_KEY);


$app->get('/dishes', function() use ($app) {
    return $app['dish.endpoint']->listResources();
})
->bind(UrlGenerator::LIST_DISHES_ROUTE_KEY);


$app->delete('/dishes/{identifier}', function($identifier) use ($app) {
    return $app['dish.endpoint']->deleteResource($identifier);
})
->bind(UrlGenerator::DELETE_DISH_ROUTE_KEY);


$app->put('/dishes/{identifier}', function($identifier) use ($app) {
    $data = file_get_contents('php://input');
    return $app['dish.endpoint']->editResource($identifier, $data);
})
->bind(UrlGenerator::EDIT_DISH_ROUTE_KEY);


$app->put('/dishes/{identifier}/ingredients', function($identifier) use ($app) {
    $data = file_get_contents('php://input');
    return $app['dish-ingredient.endpoint']->setDishIngredients($identifier, $data);
})
->bind(UrlGenerator::SET_DISH_INGREDIENTS_ROUTE_KEY);


$app->get('/dishes/{identifier}/ingredients', function($identifier) use ($app) {
    return $app['dish-ingredient.endpoint']->listDishIngredients($identifier);
})
->bind(UrlGenerator::LIST_DISH_INGREDIENTS_ROUTE_KEY);


$app->get('/dishes/{identifier}/allergens', function($identifier) use ($app) {
    return $app['dish-allergen.endpoint']->listAllergensInDish($identifier);
})
->bind(UrlGenerator::LIST_ALLERGENS_IN_DISH_ROUTE_KEY);