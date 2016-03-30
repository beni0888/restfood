<?php

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new HttpFragmentServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options'    => array(
        'driver'        => 'pdo_mysql',
        'host'          => 'localhost',
        'dbname'        => 'restfood',
        'user'          => 'restfood',
        'password'      => 'restfood',
        'charset'       => 'utf8',
        'driverOptions' => array(1002 => 'SET NAMES utf8',),
    ),
));

$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type"      => "yml",
                "namespace" => "Restfood\\Entity",
                "path"      => realpath(__DIR__."/../config/doctrine"),
            ),
        ),
    ),
));

return $app;
