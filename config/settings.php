<?php

// Should be set to 0 in production
error_reporting(E_ALL);

// Should be set to '0' in production
ini_set('display_errors', '1');

// Settings
$settings = [
  'DB' => 'sqlite:' . __DIR__ . '/../database/database.sqlite',
  'SITE_NAME' => 'Lazy Web',
  'SITE_URL' => 'http://localhost:8080',
  'ASSET_VERSION' => '1.0.0',
  'PAGE_LIMIT'=>6,
];

return $settings;