<?php

use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Middleware\ErrorMiddleware;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpException;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

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
    ) use ($app) {
        $response = $app->getResponseFactory()->createResponse();
        // Check if the error is a 404
        if ($exception instanceof HttpNotFoundException) {
            // Render your 404 template
            $view = $app->getContainer()->get(Twig::class);
            return $view->render($response, '404.twig', [
                'title' => '404 Not Found',
                'message' => 'The page you are looking for could not be found.'
            ])->withStatus(404);
        }
        // For other errors, return the default error handler response
        return $errorHandler($request, $exception, $displayErrorDetails, $logErrors, $logErrorDetails);
    };
    // Handle 404 Not Found
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
};