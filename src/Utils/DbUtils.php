<?php

namespace App\Utils;

use PDO;

/**
 * DbUtils
 * 
 * 
 * // 1. 简单相等查询
 * $filters = [
 *     'status' => 1,  // status = 1
 *     'category_id' => 5  // category_id = 5
 * ];
 * $result = paginate(1, 10, $filters);
 * 
 * // 2. 复杂条件查询
 * $filters = [
 *     'views' => ['operator' => '>=', 'value' => 100],  // views >= 100
 *     'created_at' => ['operator' => '<=', 'value' => '2025-01-01'], // created_at <= 2025-01-01
 *     'title' => ['operator' => 'LIKE', 'value' => '%php%'], // title LIKE '%php%'
 *     'status' => 1  // status = 1
 * ];
 * $result = paginate(1, 10, $filters);
 * 
 * // 3. 混合使用
 * $filters = [
 *     'views' => ['operator' => '>', 'value' => 50],  // views > 50
 *     'score' => ['operator' => '<=', 'value' => 90], // score <= 90
 *     'content' => ['operator' => 'LIKE', 'value' => '%test%'], // content LIKE '%test%'
 * ];
 * $result = paginate(1, 10, $filters);
 * 
 * Paginate(
 *   $db,
 *   table: 'posts',              // 表名
 *   page: $page,                 // 当前页
 *   perPage: $perPage,          // 每页数量
 *   filters: $filters,          // 筛选条件
 *   defaultConditions: ['yn=1'], // 默认条件
 *   orderBy: 'id DESC'          // 排序规则
 * );
 * 
 */
class DbUtils {
    
    /**
     * 通用的分页查询方法
     * 
     * @param PDO $db 数据库连接
     * @param string $table 表名
     * @param int $page 当前页
     * @param int $perPage 每页数量
     * @param array $filters 筛选条件
     * @param array $defaultConditions 默认条件
     * @param string $orderBy 排序规则
     * @return array
     */
    public static function Paginate(
        PDO $db,
        string $table,
        int $page = 1,
        int $perPage = 10,
        array $filters = [],
        array $defaultConditions = [],
        string $orderBy = 'id DESC'
    ): array {
        $offset = ($page - 1) * $perPage;
        $whereClauses = $defaultConditions; // 默认条件
        $params = [
            'limit' => $perPage,
            'offset' => $offset
        ];

        // 处理过滤条件
        if (!empty($filters)) {
          foreach ($filters as $field => $condition) {
            $field = preg_replace('/[^a-zA-Z0-9_]/', '', $field);
            if (is_array($condition) && isset($condition['operator'], $condition['value'])) {
              $operator = strtoupper($condition['operator']);
              $value = $condition['value'];
              $paramName = "{$field}_filter";
              $validOperators = ['=', '>', '<', '>=', '<=', '!=', 'LIKE'];
              if (in_array($operator, $validOperators) && $value !== null) {
                  $whereClauses[] = "$field $operator :$paramName";
                  $params[$paramName] = $value;
              }
            } elseif ($condition !== null) {
              $whereClauses[] = "$field = :$field";
              $params[$field] = $condition;
            }
          }
        }

        // 如果没有WHERE条件，默认设置为1=1
        $whereString = !empty($whereClauses) ? implode(' AND ', $whereClauses) : '1=1';

        // 查询数据
        $sql = "SELECT * FROM $table   WHERE $whereString  ORDER BY $orderBy  LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 获取总数
        $totalSql = "SELECT COUNT(*) FROM $table WHERE $whereString";
        $totalStmt = $db->prepare($totalSql);
        $filterParams = $params;
        unset($filterParams['limit'], $filterParams['offset']);
        $totalStmt->execute($filterParams);
        $total = $totalStmt->fetchColumn();

        return [
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }


    public static function WhereClausesAndParams(array $filters,array $whereClauses, array $params) {
      foreach ($filters as $field => $condition) {
        $field = preg_replace('/[^a-zA-Z0-9_]/', '', $field);
        if (is_array($condition) && isset($condition['operator'], $condition['value'])) {
            $operator = strtoupper($condition['operator']);
            $value = $condition['value'];
            $paramName = "{$field}_filter";
            $validOperators = ['=', '>', '<', '>=', '<=', '!=', 'LIKE'];
            if (in_array($operator, $validOperators) && $value !== null) {
                $whereClauses[] = "$field $operator :$paramName";
                $params[$paramName] = $value;
            }
        } elseif ($condition !== null) {
            $whereClauses[] = "$field = :$field";
            $params[$field] = $condition;
        }
      }
      return [
        'whereClauses' => $whereClauses,
        'params' => $params
      ];
    }
}
