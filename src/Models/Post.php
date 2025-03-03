<?php

namespace App\Models;

use PDO;
use App\Utils\DbUtils;


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
*     is_pinned INTEGER NOT NULL DEFAULT 0,
*     published_at DATETIME,
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

  private const SELECT_BASE = "SELECT * FROM posts WHERE yn=1 ORDER BY id DESC";
  private const INSERT_SQL = <<<SQL
        INSERT INTO posts 
        (title, slug, content, thumbnail, status, is_pinned, published_at, 
         seo_title, seo_description, seo_keywords, seo_schema_json) 
        VALUES 
        (:title, :slug, :content, :thumbnail, :status, :is_pinned, :published_at, 
         :seo_title, :seo_description, :seo_keywords, :seo_schema_json)
    SQL;


  private const UPDATE_SQL = <<<SQL
        UPDATE posts
        SET title = :title, slug = :slug, content = :content, thumbnail = :thumbnail,
            status = :status, is_pinned = :is_pinned, published_at = :published_at,
            seo_title = :seo_title, seo_description = :seo_description,
            seo_keywords = :seo_keywords, seo_schema_json = :seo_schema_json
        WHERE id = :id
    SQL;

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
        $stmt = $this->db->query(self::SELECT_BASE);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE yn=1 and id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(self::INSERT_SQL);
        $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'thumbnail' => $data['thumbnail'] ?? null,
            'status' => empty($data['status']) ? 'draft': $data['status'],
            'is_pinned' => $data['is_pinned'] ?? 0,
            'published_at' => $data['published_at'] ?? null,
            'seo_title' => empty($data['seo_title']) ? $data['title'] : $data['seo_title'],
            'seo_description' => $data['seo_description'] ?? null,
            'seo_keywords' => $data['seo_keywords'] ?? null,
            'seo_schema_json' => $data['seo_schema_json'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(self::UPDATE_SQL);
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'thumbnail' => $data['thumbnail'] ?? null,
            'status' => empty($data['status']) ? 'draft': $data['status'],
            'is_pinned' => $data['is_pinned'] ?? 0,
            'published_at' => $data['published_at'] ?? null,
            'seo_title' => empty($data['seo_title']) ? $data['title'] : $data['seo_title'],
            'seo_description' => $data['seo_description'] ?? null,
            'seo_keywords' => $data['seo_keywords'] ?? null,
            'seo_schema_json' => $data['seo_schema_json'] ?? null   
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE posts SET yn = 0 WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE yn=1 and  email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function paginate(int $page = 1, int $perPage = 10, array $filters = []): array
    {
        // return DbUtils::Paginate(
        //     $this->db,
        //     table: 'posts',              // 表名
        //     page: $page,                 // 当前页
        //     perPage: $perPage,          // 每页数量
        //     filters: $filters,          // 筛选条件
        //     defaultConditions: ['yn=1'], // 默认条件
        //     orderBy: 'id DESC'          // 排序规则
        // );

        $offset = ($page - 1) * $perPage;
        $whereClauses = ['yn = 1']; // 默认条件
        $params = [
            'limit' => $perPage,
            'offset' => $offset
        ];
        // 处理过滤条件
        if (!empty($filters)) {
            $wp = DbUtils::WhereClausesAndParams($filters, $whereClauses, $params);
            $whereClauses = $wp['whereClauses'];
            $params = $wp['params'];
        }

        // 构建SQL
        $whereString = !empty($whereClauses) ? implode(' AND ', $whereClauses) : '1=1';
        $sql = "SELECT * FROM posts  WHERE $whereString  ORDER BY id DESC  LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // 获取总记录数
        $totalSql = "SELECT COUNT(*) FROM posts  WHERE $whereString";
        $totalStmt = $this->db->prepare($totalSql);
        $filterParams = $params;
        unset($filterParams['limit'], $filterParams['offset']);
        $totalStmt->execute($filterParams);
        $total = $totalStmt->fetchColumn();

        return [
            'data' => $posts,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage)
            ]
        ];

    }
}