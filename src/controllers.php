<?php

use Restfood\Exception\ResourceNotFoundException;

$app->error(function (ResourceNotFoundException $e) use ($app) {
    $httpCode = 404;
    return $app->json(array('message' => $e->getMessage()), $httpCode);
});


$app->error(function (\Restfood\Exception\InvalidDataException $e) use ($app) {
    $httpCode = 400;
    return $app->json(array('message' => $e->getMessage()), $httpCode);
});


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    return $app->json(array('message' => 'Internal Server Error', 'code' => $e->getCode()), $code);
});

require __DIR__ . '/Controller/AllergenController.php';
require __DIR__ . '/Controller/IngredientController.php';
require __DIR__ . '/Controller/DishController.php';