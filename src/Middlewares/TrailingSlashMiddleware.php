<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class TrailingSlashMiddleware
{
  public function __construct(
      private array $excludePaths = []
  ) {}

  public function __invoke(Request $request, RequestHandler $handler): Response
  {
      $uri = $request->getUri();
      $path = $uri->getPath();
      $method = $request->getMethod();
      
      // Check for excluded paths using pattern matching
      foreach ($this->excludePaths as $pattern) {
          if (preg_match('#^' . $pattern . '#', $path)) {
              return $handler->handle($request);
          }
      }
      
      if ($method === 'GET' && $path !== '/' && substr($path, -1) !== '/') {
          $response = new Response();
          $uri = $uri->withPath($path . '/');
          
          return $response
              ->withHeader('Location', (string) $uri)
              ->withStatus(301);
      }

      return $handler->handle($request);
  }

}