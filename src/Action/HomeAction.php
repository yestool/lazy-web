<?php

namespace App\Action;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $view = Twig::fromRequest($request);
    
        return $view->render($response, 'home.html.twig', [
            'name' => 'John',
        ]);
    }
}