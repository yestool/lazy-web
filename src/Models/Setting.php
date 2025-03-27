<?php

namespace App\Models;

use PDO;


/**
 * Setting Model
 * 
 * CREATE TABLE settings (
 *   key VARCHAR(255) PRIMARY KEY,
 *   value TEXT
 * );
 * 
 */

class Setting
{
  private PDO $db;
  public const GLOBAL_HEADER = 'global_header';
  public const GLOBAL_FOOTER = 'global_footer';

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getDb(): PDO
  {
    return $this->db;
  }

  public function updateSetting($key, $data)
  {
      $stmt = $this->db->prepare("UPDATE settings SET value = :value WHERE key = :key");
      $stmt->execute(['key' => $key, 'value' => $data]);
      return $stmt->rowCount();
  }



  public function getSettingByKey($key)
  {
      $stmt = $this->db->prepare("SELECT * FROM settings WHERE key = :key");
      $stmt->execute(['key' => $key]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
  }

}