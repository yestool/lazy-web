<?php

namespace App\Controllers\Admin;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Odan\Session\SessionInterface;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use App\Services\PostService;

final class PostController extends Controller
{
    public function __construct(
        protected Twig $twig,
        private SessionInterface $session,
        private PostService $postService,
        private LoggerInterface $logger
    ) {}

  public function index(Request $request, Response $response): Response
  {
    $hxrequest = $request->getHeader('hx-target');
    $page = $request->getQueryParams()['page'] ?? 1;
    $this->logger->info('page: '. $page);
    $users = $this->postService->paginate($page);

    if ($hxrequest && $hxrequest[0] == 'table-container') {
        return $this->render($response, 'admin/post/partials/table.html.twig', [
            'data' => $users['data'], 
            'current_page' => $users['pagination']['current_page'], 
            'total_pages' => $users['pagination']['last_page']
        ]);
    } 
    return $this->render($response, 'admin/post/index.html.twig', [
        'data' => $users['data'], 
        'current_page' => $users['pagination']['current_page'], 
        'total_pages' => $users['pagination']['last_page']
    ]);
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

  public function create(Request $request, Response $response): Response
  {
      return $this->render($response, 'admin/post/create.html.twig');
  }

  public function store(Request $request, Response $response): Response
  {
      $data = $request->getParsedBody();
      // $data['password'] = 'admin'; // Post 不需要密码
      $this->postService->createPost($data); // 假设 PostService 中有 createPost 方法
      return $response->withHeader('Location', '/admin/posts')->withStatus(302);
  }
}