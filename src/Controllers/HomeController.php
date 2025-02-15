<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HomeController extends Controller
{

    public function index(Request $request, Response $response): Response
    {
        return $this->render($response, 'home.twig',[
            'name' => 'John',
        ]);
    }
}