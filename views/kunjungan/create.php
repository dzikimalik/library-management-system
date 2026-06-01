<?php $title = 'Tambah Kunjungan'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $currentRole = isset($role) ? $role : Session::get('role'); ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>kunjungan">Kunjungan</a></li><li class="breadcrumb-item active">Tambah</li></ol>
        </nav>
        <h4>Tambah Kunjungan</h4>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>kunjungan/store" method="POST">
            <?= csrf_field() ?>
            <?php if ($currentRole !== 'Anggota'): ?>
            <div class="mb-3">
                <label for="nama_pengunjung" class="form-label">Nama Pengunjung <span class="text-danger">*</span></label>
                <input type="text" name="nama_pengunjung" id="nama_pengunjung" class="form-control" required>
            </div>
            <?php else: ?>
            <div class="mb-3">
                <label class="form-label">Nama Pengunjung</label>
                <input type="text" class="form-control" value="<?= sanitize($anggota['nama'] ?? Session::get('nama')) ?>" readonly>
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                <textarea name="keperluan" id="keperluan" class="form-control" rows="3" placeholder="Membaca / Meminjam Buku / dll" required></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="<?= BASE_URL ?>kunjungan" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Batal</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
