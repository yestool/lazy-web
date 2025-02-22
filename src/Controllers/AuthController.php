<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Odan\Session\SessionInterface;
use Slim\Views\Twig;
use App\Services\UserService;
use Psr\Log\LoggerInterface;
use App\Utils\PasswordUtils;

class AuthController extends Controller
{
  public function __construct(
    protected Twig $twig,
    private UserService $userService,
    private SessionInterface $session,
    private LoggerInterface $logger
  ) {

  }

  public function gologin(Request $request, Response $response)
  {
    $user = $this->session->get('user');
    if ($user) {
      return $this->redirect($response, '/admin');
    }
    $errorMsg = $this->session->getFlash()->get('error');
    return $this->render($response, 'admin/login.twig', ['error' => $errorMsg]);
  }

  public function login(Request $request, Response $response)
  {
    $data = $request->getParsedBody();
    $email = $data['email'];
    $password = $data['password'];
    
    $user = $this->userService->getUserByEmail($email);
    if ($user && PasswordUtils::verifyPassword($password, $user['password'])) {
      $this->session->set('user', [
        'name' => $user['name'],
        'email' => $user['email']
      ]);
      return $this->redirect($response, '/admin');
    } else {
      $this->session ->getFlash()->add('error', '用户名或密码错误');
    }
    return $this->redirect($response, '/admin/login');
  }
  
  public function logout(Request $request, Response $response)
  {
    $this->session->clear();
    return $response->withHeader('Location', '/login')
                      ->withStatus(302);
  }
}