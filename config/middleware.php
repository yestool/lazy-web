<?php

use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Odan\Session\Middleware\SessionStartMiddleware;
use App\Middlewares\TrailingSlashMiddleware;
use App\Middlewares\MyTwigMiddleware;
use App\Middlewares\HtmlMinifyMiddleware;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    $trailingSlashMiddleware = new TrailingSlashMiddleware(
        ['/admin','/api'],
    );

    $app->add($trailingSlashMiddleware);

    $methodOverrideMiddleware = new MethodOverrideMiddleware();
    $app->add($methodOverrideMiddleware);


    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) use ($app, $errorHandler) {
        $response = $app->getResponseFactory()->createResponse();
        // 判断是否为POST请求且是AJAX请求
        $isAjax = $request->getMethod() === 'POST' &&  strpos($request->getHeaderLine('Content-Type'), 'application/json') !== false;

        // Check if the error is a 404
        if ($exception instanceof HttpNotFoundException) {
            if ($isAjax) {
                $payload = [
                    'success' => false,
                    'message' => '资源未找到',
                    'code' => 404
                ];
                $response->getBody()->write(json_encode($payload));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            } else {
                $view = $app->getContainer()->get(Twig::class);
                return $view->render($response, '404.twig', [
                    'title' => '404 Not Found',
                    'message' => 'The page you are looking for could not be found.'
                ])->withStatus(404);
            }
        }
        if ($isAjax) {
            $payload = [
                'success' => false,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode() ?: 500
            ];
            $response->getBody()->write(json_encode($payload));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($exception->getCode() ?: 500);
        }
        // For other errors, return the default error handler response
        return $errorHandler($request, $exception, $displayErrorDetails, $logErrors, $logErrorDetails);
    };
    // Handle 404 Not Found
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);

    //$app->add(SessionMiddleware::class);

    $app->add(SessionStartMiddleware::class);

    $app->add(MyTwigMiddleware::class);

    $app->add(HtmlMinifyMiddleware::class);
    
};