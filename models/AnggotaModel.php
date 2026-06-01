<?php

class AnggotaModel extends Model
{
    protected $table = 'anggota';

    public function getByUserId($userId)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE user_id = ?", [$userId]);
        return $stmt->fetch();
    }

    public function getByNis($nis)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE nis = ?", [$nis]);
        return $stmt->fetch();
    }

    public function getAllWithKelas()
    {
        $stmt = $this->query(
            "SELECT a.*, k.nama_kelas FROM {$this->table} a
             LEFT JOIN kelas k ON a.kelas_id = k.id"
        );
        return $stmt->fetchAll();
    }

    public function findWithRelation($id)
    {
        $stmt = $this->query(
            "SELECT a.*, k.nama_kelas FROM {$this->table} a
             LEFT JOIN kelas k ON a.kelas_id = k.id
             WHERE a.id = ?",
            [$id]
        );
        return $stmt->fetch();
    }
}
