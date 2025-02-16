<?php


return [
  'name' => 'my_app_session',
  'lifetime' => 7200,
  'path' => '/',
  'domain' => null,
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict',
  'cache_limiter' => 'nocache',
  // PHP原生session配置
  'options' => [
      'cookie_httponly' => 1,
      'use_only_cookies' => 1,
      'cookie_secure' => 1,
      'cookie_samesite' => 'Strict',
      'gc_maxlifetime' => 7200,
  ]
];