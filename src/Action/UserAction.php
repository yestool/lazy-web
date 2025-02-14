<?php

namespace App\Action;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class UserAction
{
  private UserService $userService;


  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function index(Request $request, Response $response): Response
  {
    $users = $this->userService->getAllUsers();
    $view = Twig::fromRequest($request);
    return $view ->render($response, 'user/index.html.twig', ['users' => $users]);
  }

  // 其他CRUD方法类似实现
}