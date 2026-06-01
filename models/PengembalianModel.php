<?php

class PengembalianModel extends Model
{
    protected $table = 'pengembalian';

    public function getAllWithRelation()
    {
        $stmt = $this->query(
            "SELECT pg.*, p.anggota_id, a.nama as nama_anggota, p.buku_id, b.judul as judul_buku,
                    u.username as nama_petugas, p.tgl_pinjam, p.tgl_jatuh_tempo
             FROM {$this->table} pg
             LEFT JOIN peminjaman p ON pg.peminjaman_id = p.id
             LEFT JOIN anggota a ON p.anggota_id = a.id
             LEFT JOIN buku b ON p.buku_id = b.id
             LEFT JOIN users u ON pg.user_id = u.id
             ORDER BY pg.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function countToday()
    {
        $stmt = $this->query(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(tgl_kembali) = CURDATE()"
        );
        return $stmt->fetch()['total'];
    }
}
