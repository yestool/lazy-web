<?php

namespace App\Utils;

use PDO;

class QueryBuilder
{
    private PDO $db;
    private string $table;
    private array $columns = [];
    private array $values = [];
    private array $set = [];
    private array $where = [];
    private int $limit = 0;
    private int $offset = 0;
    private string $orderBy = '';
    private string $orderDirection = 'ASC';

    public function __construct(PDO $db, string $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    public function insert(array $data): self
    {
        $this->columns = array_keys($data);
        $this->values = array_values($data);
        return $this;
    }

    public function update(array $data): self
    {
        foreach ($data as $column => $value) {
            $this->set[] = "$column = :{$column}";
        }
        $this->values = array_merge($this->values, $data);
        return $this;
    }

    public function where(string $column, string $operator, $value): self
    {
        $this->where[] = "$column $operator :{$column}_where";
        $this->values["{$column}_where"] = $value;
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = $column;
        $this->orderDirection = $direction;
        return $this;
    }


    public function select(string $columns = '*'): self
    {
        $this->columns = explode(',', $columns);
        return $this;
    }


    public function getSql(): string
    {
        $sql = '';
        if (!empty($this->columns) && !empty($this->values) && empty($this->set) && empty($this->where)) {
            // INSERT
            $sql = "INSERT INTO {$this->table} (" . implode(', ', $this->columns) . ") VALUES (:" . implode(', :', $this->columns) . ")";
        } elseif (!empty($this->set) && !empty($this->where) && empty($this->columns) && empty($this->values)) {
            // UPDATE
            $sql = "UPDATE {$this->table} SET " . implode(', ', $this->set) . " WHERE " . implode(' AND ', $this->where);
        } elseif (!empty($this->columns) && empty($this->set) && !empty($this->where) && empty($this->values)) {
            // SELECT
            $sql = "SELECT " . implode(', ', $this->columns) . " FROM {$this->table} WHERE " . implode(' AND ', $this->where);
            if ($this->orderBy) {
                $sql .= " ORDER BY {$this->orderBy} {$this->orderDirection}";
            }
            if ($this->limit > 0) {
                $sql .= " LIMIT {$this->limit}";
            }
            if ($this->offset > 0) {
                $sql .= " OFFSET {$this->offset}";
            }

        }


        return $sql;
    }

    public function execute(): mixed
    {
        $sql = $this->getSql();
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->values);

        if (strpos(trim(strtoupper($sql)), 'INSERT') === 0) {
            return $this->db->lastInsertId();
        } elseif (strpos(trim(strtoupper($sql)), 'SELECT') === 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // 或者 fetch/fetchColumn，根据需要
        }

        return $stmt->rowCount();
    }


    public function fetch()
    {
        $sql = $this->getSql();
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->values);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        $sql = $this->getSql();
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->values);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}