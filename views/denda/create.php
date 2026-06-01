<?php $title = 'Tambah Denda'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>denda">Data Denda</a></li><li class="breadcrumb-item active">Tambah Denda</li></ol>
        </nav>
        <h4>Tambah Denda</h4>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>denda/store" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="pengembalian_id" class="form-label">Pengembalian <span class="text-danger">*</span></label>
                <select name="pengembalian_id" id="pengembalian_id" class="form-select" required>
                    <option value="">-- Pilih Pengembalian --</option>
                    <?php foreach ($pengembalian as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= sanitize($p['nama_anggota'] ?? '-') ?> - <?= sanitize($p['judul_buku'] ?? '-') ?> (<?= formatDate($p['tgl_kembali']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah_denda" class="form-label">Jumlah Denda <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="jumlah_denda" id="jumlah_denda" class="form-control" placeholder="Masukkan jumlah denda" required>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="<?= BASE_URL ?>denda" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Batal</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
