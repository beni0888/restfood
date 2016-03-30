<?php

use Restfood\RESTFul\Url\UrlGenerator;

$app->post('/allergens', function() use ($app) {
    $data = file_get_contents('php://input');
    return $app['allergen.endpoint']->createResource($data);
})
->bind(UrlGenerator::CREATE_ALLERGEN_ROUTE_KEY);


$app->get('/allergens', function() use ($app) {
    return $app['allergen.endpoint']->listResources();
})
->bind(UrlGenerator::LIST_ALLERGENS_ROUTE_KEY);


$app->get('/allergens/{identifier}', function($identifier) use ($app) {
    return $app['allergen.endpoint']->showResource($identifier);
})
->bind(UrlGenerator::SHOW_ALLERGEN_ROUTE_KEY);


$app->delete('/allergens/{identifier}', function($identifier) use ($app) {
    return $app['allergen.endpoint']->deleteResource($identifier);
})
->bind(UrlGenerator::DELETE_ALLERGEN_ROUTE_KEY);


$app->put('/allergens/{identifier}', function($identifier) use ($app) {
    $data = file_get_contents('php://input');
    return $app['allergen.endpoint']->editResource($identifier, $data);
})
->bind(UrlGenerator::EDIT_ALLERGEN_ROUTE_KEY);


$app->get('/allergens/{identifier}/dishes', function($identifier) use ($app) {
    return $app['dish-allergen.endpoint']->listDishesWithAllergen($identifier);
})
->bind(UrlGenerator::LIST_DISHES_WITH_ALLERGEN_ROUTE_KEY);