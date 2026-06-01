<?php

class BukuModel extends Model
{
    protected $table = 'buku';

    public function getAvailable()
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE stok > 0");
        return $stmt->fetchAll();
    }

    public function updateStok($id, $amount)
    {
        $this->query("UPDATE {$this->table} SET stok = stok + ? WHERE id = ?", [$amount, $id]);
    }

    public function search($keyword)
    {
        $keyword = "%$keyword%";
        $stmt = $this->query(
            "SELECT * FROM {$this->table} WHERE judul LIKE ? OR pengarang LIKE ? OR penerbit LIKE ?",
            [$keyword, $keyword, $keyword]
        );
        return $stmt->fetchAll();
    }
}
