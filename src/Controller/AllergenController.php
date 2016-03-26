<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

$app->post('/allergens', function() use ($app) {
    $data = file_get_contents('php://input');
    $allergen = $app['allergen.manager']->create($data);
    $urlType = UrlGeneratorInterface::ABSOLUTE_URL;
    $url = $app['url_generator']->generate('show_allergen', array('identifier' => $allergen->obtainIdentifier()), $urlType);
    $httpCode = 201;

    return $app->json($allergen, $httpCode, array('Location' => $url));
})
->bind('create_allergen');


$app->get('/allergens', function() use ($app) {
    $httpCode = 200;

    return $app->json($app['allergen.manager']->showList(), $httpCode);
})
->bind('list_allergens');


$app->get('/allergens/{identifier}', function($identifier) use ($app) {
    $allergen = $app['allergen.manager']->show($identifier);

    return $app->json($allergen);
})
->bind('show_allergen');


$app->delete('/allergens/{identifier}', function($identifier) use ($app) {
    $app['allergen.manager']->remove($identifier);
    $httpCode = 204;

    return new Response('', $httpCode);
})
->bind('delete_allergen');


$app->put('/allergens/{identifier}', function($identifier) use ($app) {
    $data = file_get_contents('php://input');
    $allergen = $app['allergen.manager']->edit($identifier, $data);
    $httpCode = 200;

    return $app->json($allergen, $httpCode);
})
->bind('edit_allergen');