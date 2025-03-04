<?php

namespace App\Controllers\Admin;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Odan\Session\SessionInterface;
use Slim\Views\Twig;
use App\Services\PostService;
use App\Utils\CommonUtils;

final class SettingController extends Controller
{
    public function __construct(
        protected Twig $twig,
        private SessionInterface $session,
        private PostService $postService
    ) {}


    public function index(Request $request, Response $response): Response
    {
        return $this->render($response, 'admin/settings.html.twig');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        
        // 返回成功响应
        $successResponse = [
            'success' => true,
            'message' => '文章创建成功',
            'data' => $article
        ];
        $response->getBody()->write(json_encode($successResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}