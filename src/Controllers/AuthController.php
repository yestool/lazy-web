<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Odan\Session\SessionInterface;
use Slim\Views\Twig;
use App\Services\UserService;
use Psr\Log\LoggerInterface;

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
    return $this->render($response, 'admin/login.twig');
  }

  public function login(Request $request, Response $response)
  {
    $data = $request->getParsedBody();
    $email = $data['email'];
    $password = $data['password'];
    
    $user = $this->userService->getUserByEmail($email);
    if ($user) {
      $this->session->set('user', [
        'id' => 1,
        'name' => $user['name'],
        'email' => $user['email']
      ]);
        
      return $this->redirect($response, '/admin');
    }
    
    return $this->redirect($response, '/admin/login?error=1');
  }
  
  public function logout(Request $request, Response $response)
  {
    $this->session->destroy();
    return $response->withHeader('Location', '/login')
                      ->withStatus(302);
  }
}