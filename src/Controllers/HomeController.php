<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\ConsoleLogger;
use Slim\Views\Twig;

final class HomeController extends Controller
{

    public function __construct(
        protected Twig $twig,
    ) {}

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

    public function ogImage(Request $request, Response $response): Response
    {
        // 获取查询参数
        $queryParams = $request->getQueryParams();
        $title = $queryParams['title'] ?? '这是一个Open Graph图片生成器';
        $siteName = $queryParams['siteName'] ?? '默认网站名称';
        $bgColor = $queryParams['bgColor'] ?? '#ffffff';
        $textColor = $queryParams['textColor'] ?? '#000000';
       
        // 渲染SVG模板
        $svgContent = $this->twig->fetch('og-image.svg.twig', [
            'title' => htmlspecialchars($title),
            'siteName' => htmlspecialchars($siteName),
            'siteUrl' => 'example.com >>',
            'bgColor' => htmlspecialchars($bgColor),
            'textColor' => htmlspecialchars($textColor),
        ]);

        // 使用Imagick转换SVG为PNG
        $imagick = new \Imagick();

        // 配置字体路径
        $fontPath = __DIR__ . '/../../public/font/SourceHanSansCN-Normal.otf';
        $imagick->setFont($fontPath);
    
        $imagick->readImageBlob($svgContent);

        // 设置字体抗锯齿
        $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_ACTIVATE);
        $imagick->setBackgroundColor(new \ImagickPixel('transparent'));
        $imagick->setImageFormat('png');
        
        // 设置图片大小（Open Graph推荐尺寸：1200x630）
        $imagick->resizeImage(1200, 630, \Imagick::FILTER_LANCZOS, 1);
        // 获取图片二进制数据
        $imageBlob = $imagick->getImageBlob();
        // 设置响应头
        $response = $response->withHeader('Content-Type', 'image/png');
        $response = $response->withHeader('Cache-Control', 'public, max-age=3600');
        // 写入图片数据到响应体
        $response->getBody()->write($imageBlob);
        return $response;
    }
}