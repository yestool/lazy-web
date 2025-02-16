<?php

namespace App\Middlewares;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class AuthMiddleware
{
    private $session;
    private $options;
    private Twig $twig;
    
    public function __construct(
        SessionInterface $session,
        Twig $twig,
        array $options = []
    ) {
        $this->session = $session;
        $this->twig = $twig;
        $this->options = array_merge([
            'loginUrl' => '/login',
            'redirectParam' => 'redirect',
            'excludePaths' => ['/login', '/register', '/forgot-password']
        ], $options);
    }

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        // 获取当前路径
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $path = $route ? $route->getPattern() : '';

        // 检查是否在排除路径中
        if (in_array($path, $this->options['excludePaths'])) {
            return $handler->handle($request);
        }

        // 检查用户是否已登录
        $user = $this->session->get('user');        
        if (!$user) {
            // 保存当前URL用于登录后重定向
            $currentUrl = (string)$request->getUri();
            $loginUrl = $this->options['loginUrl'];
            if ($currentUrl !== $loginUrl) {
                $loginUrl .= '?' . $this->options['redirectParam'] . '=' . urlencode($currentUrl);
            }
            $response = new Response();
            return $response->withHeader('Location', $loginUrl)->withStatus(302);
        }

        // 可以添加额外的权限检查
        if ($this->checkPermissions($user, $path)) {
            return $handler->handle($request);
        }
        $response = new Response();
        return $response->withStatus(403);
    }

    private function checkPermissions(array $user, string $path): bool
    {
        // 实现权限检查逻辑
        return true;
    }
}