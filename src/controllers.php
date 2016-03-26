<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Restfood\Exception\ResourceNotFoundException;

//Request::setTrustedProxies(array('127.0.0.1'));



//$app->get('/', function () use ($app) {
//    return $app['twig']->render('index.html', array());
//})
//->bind('homepage')
//;

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

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});


$app->post('/allergens', function() use ($app) {
    $data = file_get_contents('php://input');
    $allergen = $app['allergen.manager']->create($data);
    $urlType = UrlGeneratorInterface::ABSOLUTE_URL;
    $url = $app['url_generator']->generate('show_allergen', array('identifier' => $allergen->getUuid()), $urlType);
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