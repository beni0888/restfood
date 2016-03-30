<?php

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__ . "/../vendor/autoload.php";

// Add test command builders to loader
$loader->addPsr4('Restfood\\Test\\', __DIR__ . '/Restfood/Test');