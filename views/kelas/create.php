<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>kelas">Data Kelas</a></li>
                <li class="breadcrumb-item active">Tambah Kelas</li>
            </ol>
        </nav>
        <h4>Tambah Kelas</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>kelas/store" method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kelas" class="form-control" placeholder="X, XI, XII" required>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="<?= BASE_URL ?>kelas" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
