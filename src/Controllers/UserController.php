<?php

namespace App\Controllers;
use Slim\Views\Twig;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


final class UserController extends Controller
{
  public function __construct(
    protected Twig $twig,
    private UserService $userService,
  ) {

  }

  public function index(Request $request, Response $response): Response
  {
    $users = $this->userService->getAllUsers();
    return $this->render($response, 'users/index.twig', ['users' => $users]);
  }

  public function create(Request $request, Response $response): Response
    {
        return $this->render($response, 'users/create.twig');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $data['password'] = 'admin';
        $this->userService->createUser($data);
        return $response->withHeader('Location', '/users')->withStatus(302);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $user = $this->userService->getUserById($args['id']);
        return $this->render($response, 'users/show.twig', ['user' => $user]);
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $user = $this->userService->getUserById($args['id']);
        return $this->render($response, 'users/edit.twig', ['user' => $user]);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $this->userService->updateUser($args['id'], $data);
        return $response->withHeader('Location', '/users')->withStatus(302);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $this->userService->deleteUser($args['id']);
        return $response->withHeader('Location', '/users')->withStatus(302);
    }
}