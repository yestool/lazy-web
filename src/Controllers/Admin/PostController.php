<?php

namespace App\Controllers\Admin;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Odan\Session\SessionInterface;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use App\Services\PostService;
use App\Utils\CommonUtils;

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
    $this->logger->info('total_pages: '. $users['pagination']['last_page']);

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
    if (empty($data['title']) || empty($data['content'])) {
        $errorResponse = [
            'success' => false,
            'message' => '标题和内容不能为空'
        ];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }
    
    
    $published_at = empty($data['published_at']) ? CommonUtils::DateNow() : $data['published_at'] ;
    $published_at = CommonUtils::DateFormat($published_at);
    $this->logger->info('published_at: '. $published_at);
    $this->logger->info('title: '. $data['title']);
    $articleId = $this->postService->createPost($data);

    // 处理数据（这里可以添加数据库操作等）
    $article = [
        'id' => $articleId,
    ];
    
    // 返回成功响应
    $successResponse = [
        'success' => true,
        'message' => '文章创建成功',
        'data' => $article
    ];
    $response->getBody()->write(json_encode($successResponse));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
  }
}