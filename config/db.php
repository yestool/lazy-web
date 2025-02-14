<?php

use App\Config;

return function (Config $config) {
  $db = new PDO('sqlite:' . $config['db']['path']);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $db;
};