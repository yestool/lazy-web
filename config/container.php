<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use PDO;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use App\Controllers;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },
    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);
        // Register routes
        (require __DIR__ . '/routes.php')($app);

        // Register middleware
        (require __DIR__ . '/middleware.php')($app);

        return $app;
    },
    PDO::class => function () {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
        return $pdo;
    },
    Twig::class => function () {
        $twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
        return $twig;
    }
];