<?php

class DendaModel extends Model
{
    protected $table = 'denda';

    public function getAllWithRelation()
    {
        $stmt = $this->query(
            "SELECT d.*, p.anggota_id, a.nama as nama_anggota, p.buku_id, b.judul as judul_buku, pn.tgl_kembali
             FROM {$this->table} d
             LEFT JOIN pengembalian pn ON d.pengembalian_id = pn.id
             LEFT JOIN peminjaman p ON pn.peminjaman_id = p.id
             LEFT JOIN anggota a ON p.anggota_id = a.id
             LEFT JOIN buku b ON p.buku_id = b.id
             ORDER BY d.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getByAnggota($anggotaId)
    {
        $stmt = $this->query(
            "SELECT d.*, p.buku_id, b.judul as judul_buku, pn.tgl_kembali
             FROM {$this->table} d
             LEFT JOIN pengembalian pn ON d.pengembalian_id = pn.id
             LEFT JOIN peminjaman p ON pn.peminjaman_id = p.id
             LEFT JOIN buku b ON p.buku_id = b.id
             WHERE p.anggota_id = ?
             ORDER BY d.created_at DESC",
            [$anggotaId]
        );
        return $stmt->fetchAll();
    }

    public function sumUnpaid()
    {
        $stmt = $this->query(
            "SELECT COALESCE(SUM(jumlah_denda), 0) as total FROM {$this->table} WHERE status_bayar = 'belum'"
        );
        return $stmt->fetch()['total'];
    }

    public function countUnpaid()
    {
        $stmt = $this->query(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE status_bayar = 'belum'"
        );
        return $stmt->fetch()['total'];
    }
}
