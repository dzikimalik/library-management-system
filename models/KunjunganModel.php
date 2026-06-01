<?php

class KunjunganModel extends Model
{
    protected $table = 'kunjungan';

    public function getAllWithRelation()
    {
        $stmt = $this->query(
            "SELECT k.*, a.nama as nama_anggota
             FROM {$this->table} k
             LEFT JOIN anggota a ON k.anggota_id = a.id
             ORDER BY k.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function countToday()
    {
        $stmt = $this->query(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(tgl_kunjungan) = CURDATE()"
        );
        return $stmt->fetch()['total'];
    }

    public function countPerDay($days = 7)
    {
        $stmt = $this->query(
            "SELECT DATE(tgl_kunjungan) as tanggal, COUNT(*) as total
             FROM {$this->table}
             WHERE tgl_kunjungan >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             GROUP BY DATE(tgl_kunjungan)
             ORDER BY tgl_kunjungan ASC",
            [$days - 1]
        );
        return $stmt->fetchAll();
    }

    public function countAll()
    {
        $stmt = $this->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'];
    }
}
