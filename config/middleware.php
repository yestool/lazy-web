<?php

use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    $methodOverrideMiddleware = new MethodOverrideMiddleware();
    $app->add($methodOverrideMiddleware);

    // Handle exceptions
    $app->addErrorMiddleware(true, true, true);

                // Create Twig
                $twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);

                // Add Twig-View Middleware
                $app->add(TwigMiddleware::create($app, $twig));
};