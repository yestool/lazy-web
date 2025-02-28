<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\ConsoleLogger;

final class HomeController extends Controller
{

    public function index(Request $request, Response $response): Response
    {

        $logger = ConsoleLogger::getInstance();
    
        $logger->info("这是一个信息消息");
        $logger->warning("这是一个警告消息");
        $logger->error("这是一个错误消息");

        // 输出复杂数据
        $array = ["name" => "Grok", "version" => 3];
        $logger->info($array);

        return $this->render($response, 'home.twig',[
            'name' => 'John',
        ]);
    }
}