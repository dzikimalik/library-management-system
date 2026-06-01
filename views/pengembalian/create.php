<?php $title = 'Proses Pengembalian'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>pengembalian">Pengembalian</a></li><li class="breadcrumb-item active">Proses</li></ol>
        </nav>
        <h4>Proses Pengembalian</h4>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>pengembalian/store" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="peminjaman_id" class="form-label">Peminjaman <span class="text-danger">*</span></label>
                <select name="peminjaman_id" id="peminjaman_id" class="form-select" required>
                    <option value="">-- Pilih Data Peminjaman --</option>
                    <?php foreach ($peminjaman as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= sanitize($p['nama_anggota'] ?? 'Anggota #' . $p['anggota_id']) ?> - <?= sanitize($p['judul_buku'] ?? 'Buku #' . $p['buku_id']) ?> - <?= isset($p['tgl_pinjam']) ? formatDate($p['tgl_pinjam']) : '-' ?> - <?= isset($p['tgl_jatuh_tempo']) ? formatDate($p['tgl_jatuh_tempo']) : '-' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i>
                Denda otomatis dihitung Rp1.000/hari keterlambatan.
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i>Proses Pengembalian</button>
                <a href="<?= BASE_URL ?>pengembalian" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Batal</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
