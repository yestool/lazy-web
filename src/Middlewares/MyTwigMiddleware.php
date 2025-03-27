<?php

namespace App\Middlewares;

use App\Models\Setting;
use App\Services\SettingService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class MyTwigMiddleware 
{
    private Twig $twig;
    private SettingService $settingService;
    
    public function __construct(Twig $twig, SettingService $settingService)
    {
      $this->twig = $twig;
      $this->settingService = $settingService;
    }

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface 
    {
      $this->twig->getEnvironment()->addGlobal('uri', $request->getUri()->getPath());

      $globalHeader = $this->settingService->getSettingByKey(Setting::GLOBAL_HEADER);
      $globalFooter = $this->settingService->getSettingByKey(Setting::GLOBAL_FOOTER);

      $this->twig->getEnvironment()->addGlobal('GLOBAL_HEADER', $globalHeader['value'] ?? '');
      $this->twig->getEnvironment()->addGlobal('GLOBAL_FOOTER', $globalFooter['value'] ?? '');

      return $handler->handle($request);
    }
}