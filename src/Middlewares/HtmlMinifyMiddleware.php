<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use voku\helper\HtmlMin;

class HtmlMinifyMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $contentType = $response->getHeaderLine('Content-Type');

        // 仅对 HTML 内容压缩
        if (strpos($contentType, 'text/html') !== false) {
            $body = (string) $response->getBody();
            $htmlMin = new HtmlMin();
            // 不要移除引号
            //$htmlMin->doRemoveOmittedQuotes(false); // 关闭引号移除功能
            $minifiedHtml = $htmlMin->minify($body);
            $response = $response->withBody(
                (new \Slim\Psr7\Stream(fopen('php://temp', 'r+')))
            );
            $response->getBody()->write($minifiedHtml);
        }

        return $response;
    }
}
