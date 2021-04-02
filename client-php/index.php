<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require 'vendor/autoload.php';

    $containerBuilder = new ContainerBuilder();

    session_start();
    
    $settings = require __DIR__ . '/src/settings.php';

    $settings($containerBuilder);
    $container = $containerBuilder->build();
    AppFactory::setContainer($container);
    $app = AppFactory::create();

    // config
    require __DIR__ . '/src/config.php';

    // Register routes
    $routes = require __DIR__ . '/src/routes.php';
    $routes($app);

    // Add error middleware
    $app->addErrorMiddleware(true, true, true);

    $app->run();