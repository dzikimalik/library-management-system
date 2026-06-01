<?php

class AdminModel extends Model
{
    protected $table = 'admin';

    public function getByUserId($userId)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE user_id = ?", [$userId]);
        return $stmt->fetch();
    }
}
