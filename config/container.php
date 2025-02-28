<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use App\Middlewares\AuthMiddleware;
use Psr\Log\LoggerInterface;
use App\Extensions\TwigExtension;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },
    'sessionConfig' => function (ContainerInterface $container) {
        return require __DIR__ . '/session.php';
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
    SessionInterface::class => function (ContainerInterface $container) {
        $session_config = $container->get('sessionConfig');
        // 创建session实例
        $session = new PhpSession([
            'name' => $session_config['name'],
            'lifetime' => $session_config['lifetime'],
            'path' => $session_config['path'],
            'domain' => $session_config['domain'],
            'secure' => $session_config['secure'],
            'httponly' => $session_config['httponly'],
            'cache_limiter' => $session_config['cache_limiter'],
        ]);
        // 设置PHP原生session选项
        foreach ($session_config['options'] as $key => $value) {
            ini_set('session.' . $key, $value);
        }
        
        return $session;
    },
    SessionManagerInterface::class => function (ContainerInterface $container) {
        return $container->get(SessionInterface::class);
    },
    Twig::class => function (ContainerInterface $container) {
        $twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
        $twig->addExtension(new TwigExtension());
        $twig->getEnvironment()->addGlobal('session', $container->get(SessionInterface::class));
        return $twig;
    },
    AuthMiddleware::class => function (ContainerInterface $container) {
        return new AuthMiddleware(
            $container->get(SessionInterface::class),
            $container->get(Twig::class),
            [
                'loginUrl' => '/admin/login',
                'excludePaths' => [
                    '/admin/login',
                    '/admin/forgot-password'
                ]
            ]
        );
    },
    
];