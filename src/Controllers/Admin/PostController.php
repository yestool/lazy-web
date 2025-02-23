<?php

namespace App\Controllers\Admin;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Odan\Session\SessionInterface;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;

final class PostController extends Controller
{
    public function __construct(
        protected Twig $twig,
        private SessionInterface $session,
        private LoggerInterface $logger
    ) {}

  public function index(Request $request, Response $response): Response
  {
      return $this->render($response, 'admin/post/index.html.twig');
  }

  public function forms(Request $request, Response $response): Response
  {
      return $this->render($response, 'admin/forms.twig');
  }

  
  public function tables(Request $request, Response $response): Response
  {
      return $this->render($response, 'admin/tables.twig');
  }

  public function buttons(Request $request, Response $response): Response
  {
      return $this->render($response, 'admin/buttons.twig');
  }

  public function ui(Request $request, Response $response): Response
  {
      return $this->render($response, 'admin/ui.twig');
  }

  public function modals(Request $request, Response $response): Response
  {
      return $this->render($response, 'admin/modals.twig');
  }

  public function login(Request $request, Response $response): Response
  {
    return $this->render($response, 'admin/login.twig');
  }
}