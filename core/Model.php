<?php
require_once __DIR__ . '/Database.php';

class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all()
    {
        $results = $this->db->query("SELECT * FROM {$this->table}");
        return $results->fetchAll();
    }

    public function find($id)
    {
        $results = $this->db->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
        return $results->fetch();
    }

    public function where($column, $value)
    {
        $results = $this->db->query("SELECT * FROM {$this->table} WHERE $column = ?", [$value]);
        return $results->fetchAll();
    }

    public function create($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $this->db->query("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)", array_values($data));
        return $this->db->getConnection()->lastInsertId();
    }

    public function update($id, $data)
    {
        $sets = implode(', ', array_map(function ($col) {
            return "$col = ?";
        }, array_keys($data)));
        $params = array_values($data);
        $params[] = $id;
        $this->db->query("UPDATE {$this->table} SET $sets WHERE id = ?", $params);
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }

    public function count()
    {
        $results = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $results->fetch()['total'];
    }

    protected function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }
}
