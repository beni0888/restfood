<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


$app->post('/ingredients', function() use ($app) {
    $data = file_get_contents('php://input');
    $ingredient = $app['ingredient.manager']->create($data);
    $urlType = UrlGeneratorInterface::ABSOLUTE_URL;
    $url = $app['url_generator']->generate('show_ingredient', array('identifier' => $ingredient->obtainIdentifier()), $urlType);
    $httpCode = 201;

    return $app->json($ingredient, $httpCode, array('Location' => $url));
})
->bind('create_ingredient');


$app->get('/ingredients/{identifier}', function($identifier) use ($app) {
    $ingredient = $app['ingredient.manager']->show($identifier);

    return $app->json($ingredient);
})
->bind('show_ingredient');


$app->get('/ingredients', function() use ($app) {
    $httpCode = 200;

    return $app->json($app['ingredient.manager']->showList(), $httpCode);
})
->bind('list_ingredients');

$app->delete('/ingredients/{identifier}', function($identifier) use ($app) {
    $app['ingredient.manager']->remove($identifier);
    $httpCode = 204;

    return new Response('', $httpCode);
})
->bind('delete_ingredient');


$app->put('/ingredients/{identifier}', function($identifier) use ($app) {
    $data = file_get_contents('php://input');
    $ingredient = $app['ingredient.manager']->edit($identifier, $data);
    $httpCode = 200;

    return $app->json($ingredient, $httpCode);
})
->bind('edit_ingredient');