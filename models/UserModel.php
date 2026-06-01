<?php

class UserModel extends Model
{
    protected $table = 'users';

    public function getByUsername($username)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE username = ?", [$username]);
        return $stmt->fetch();
    }

    public function getWithRole($id)
    {
        $stmt = $this->query(
            "SELECT u.*, r.nama_role FROM {$this->table} u
             JOIN roles r ON u.role_id = r.id
             WHERE u.id = ?",
            [$id]
        );
        return $stmt->fetch();
    }

    public function getAllWithRole()
    {
        $stmt = $this->query(
            "SELECT u.*, r.nama_role FROM {$this->table} u
             JOIN roles r ON u.role_id = r.id"
        );
        return $stmt->fetchAll();
    }
}
