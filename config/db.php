<?php


use PDO;

return function () {
  $db = new PDO('sqlite:' . __DIR__ . '/../database.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $db;
};