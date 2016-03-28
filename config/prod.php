<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

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
   return new \Restfood\Validation\AllergenValidator($app['allergen.repository'], $app['validator']);
});

$app['ingredient.validator'] = $app->share(function($app) {
    return new Restfood\Validation\ResourceValidator($app['ingredient.repository'], $app['validator']);
});

$app['dish.validator'] = $app->share(function($app) {
    return new Restfood\Validation\ResourceValidator($app['dish.repository'], $app['validator']);
});

// managers
$app['allergen.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\AllergenManager($app['allergen.repository'], $app['allergen.validator']);
});

$app['ingredient.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\ResourceManager('Restfood\Entity\Ingredient', 'Ingredient',
        $app['ingredient.repository'], $app['ingredient.validator']);
});

$app['dish.manager'] = $app->share(function($app) {
    return new \Restfood\Manager\ResourceManager('Restfood\Entity\Dish', 'Dish',
        $app['dish.repository'], $app['dish.validator']);
});