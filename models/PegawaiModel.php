<?php

class PegawaiModel extends Model
{
    protected $table = 'pegawai';

    public function getByUserId($userId)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE user_id = ?", [$userId]);
        return $stmt->fetch();
    }
}
