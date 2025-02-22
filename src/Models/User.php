<?php

namespace App\Models;

use PDO;


/**
 * User Model
 * 
 * CREATE TABLE users (
 *  id INTEGER PRIMARY KEY AUTOINCREMENT,
 *  name VARCHAR(255) NOT NULL,
 *  email VARCHAR(255) UNIQUE NOT NULL,
 *  password VARCHAR(255) NOT NULL,
 *  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
 *  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
 * );
 * 
 */

class User
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function all()
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE yn=1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE yn=1 and id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $stmt->execute(['id' => $id, 'name' => $data['name'], 'email' => $data['email']]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET yn = 0 WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE yn=1 and  email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}