<?php

namespace App\Models;

use PDO;

class User
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getAll()
  {
    $stmt = $this->db->query('SELECT * FROM users');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // 其他CRUD方法类似实现
}