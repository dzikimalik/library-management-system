<?php

class PeminjamanModel extends Model
{
    protected $table = 'peminjaman';

    public function getAllWithRelation()
    {
        $stmt = $this->query(
            "SELECT p.*, a.nama as nama_anggota, b.judul as judul_buku, u.username as nama_petugas
             FROM {$this->table} p
             LEFT JOIN anggota a ON p.anggota_id = a.id
             LEFT JOIN buku b ON p.buku_id = b.id
             LEFT JOIN users u ON p.user_id = u.id"
        );
        return $stmt->fetchAll();
    }

    public function getActive()
    {
        $stmt = $this->query(
            "SELECT p.*, a.nama as nama_anggota, b.judul as judul_buku, u.username as nama_petugas
             FROM {$this->table} p
             LEFT JOIN anggota a ON p.anggota_id = a.id
             LEFT JOIN buku b ON p.buku_id = b.id
             LEFT JOIN users u ON p.user_id = u.id
             WHERE p.status = 'dipinjam' OR p.status = 'terlambat'"
        );
        return $stmt->fetchAll();
    }

    public function getByAnggota($anggotaId)
    {
        $stmt = $this->query(
            "SELECT p.*, b.judul as judul_buku FROM {$this->table} p
             LEFT JOIN buku b ON p.buku_id = b.id
             WHERE p.anggota_id = ?",
            [$anggotaId]
        );
        return $stmt->fetchAll();
    }

    public function countActive()
    {
        $stmt = $this->query(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'dipinjam' OR status = 'terlambat'"
        );
        return $stmt->fetch()['total'];
    }

    public function countToday()
    {
        $stmt = $this->query(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(tgl_pinjam) = CURDATE()"
        );
        return $stmt->fetch()['total'];
    }

    public function getLate()
    {
        $stmt = $this->query(
            "SELECT * FROM {$this->table} WHERE tgl_jatuh_tempo < CURDATE() AND status = 'dipinjam'"
        );
        return $stmt->fetchAll();
    }
}
