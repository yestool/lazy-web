<?php

namespace App\Models;

use PDO;


/**
 * Post Model
 * 
* CREATE TABLE posts (
*     id INTEGER PRIMARY KEY AUTOINCREMENT,
*     title VARCHAR(255) NOT NULL,
*     slug VARCHAR(255) UNIQUE NOT NULL,
*     content TEXT,
*     thumbnail VARCHAR(255),
*     status VARCHAR(20) NOT NULL DEFAULT 'draft', -- draft, published, archived
*     published_at DATETIME,
*     category_id INTEGER,
*     seo_title VARCHAR(255),
*     seo_description TEXT,
*     seo_keywords TEXT,
*     seo_schema_json TEXT,
*     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
*     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
*     yn INTEGER NOT NULL DEFAULT 1,
*     FOREIGN KEY (category_id) REFERENCES categories(id)
* );
 * 
 */

class Post
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getDb(): PDO
  {
    return $this->db;
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

    public function paginate(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare("SELECT * FROM users WHERE yn=1 LIMIT :limit OFFSET :offset");
        $stmt->execute(['limit' => $perPage, 'offset' => $offset]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // 获取总记录数
        $totalStmt = $this->db->query("SELECT COUNT(*) FROM users WHERE yn=1");
        $total = $totalStmt->fetchColumn();

        return [
            'data' => $users,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }
}