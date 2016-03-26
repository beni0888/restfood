<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

// repositories
$app['allergen.repository'] = $app->share(function($app) {
    return $app['orm.em']->getRepository('Restfood\Entity\Allergen');
});

// validators
$app['allergen.validator'] = $app->share(function($app) {
   return new \Restfood\Validation\AllergenValidator($app['allergen.repository'], $app['validator']);
});

// managers
$app['allergen.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\AllergenManager($app['allergen.repository'], $app['allergen.validator']);
});
