<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


$app->post('/dishes', function() use ($app) {
    $data = file_get_contents('php://input');
    $dish = $app['dish.manager']->create($data);
    $urlType = UrlGeneratorInterface::ABSOLUTE_URL;
    $url = $app['url_generator']->generate('show_dish', array('identifier' => $dish->obtainIdentifier()), $urlType);
    $httpCode = 201;

    return $app->json($dish, $httpCode, array('Location' => $url));
})
->bind('create_dish');


$app->get('/dishes/{identifier}', function($identifier) use ($app) {
    $dish = $app['dish.manager']->show($identifier);

    return $app->json($dish);
})
->bind('show_dish');


$app->get('/dishes', function() use ($app) {
    $httpCode = 200;

    return $app->json($app['dish.manager']->showList(), $httpCode);
})
->bind('list_dishes');

$app->delete('/dishes/{identifier}', function($identifier) use ($app) {
    $app['dish.manager']->remove($identifier);
    $httpCode = 204;

    return new Response('', $httpCode);
})
->bind('delete_dish');


$app->put('/dishes/{identifier}', function($identifier) use ($app) {
    $data = file_get_contents('php://input');
    $dish = $app['dish.manager']->edit($identifier, $data);
    $httpCode = 200;

    return $app->json($dish, $httpCode);
})
->bind('edit_dish');