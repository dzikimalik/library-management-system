<?php

class KepalaModel extends Model
{
    protected $table = 'kepala';

    public function getByUserId($userId)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE user_id = ?", [$userId]);
        return $stmt->fetch();
    }
}
