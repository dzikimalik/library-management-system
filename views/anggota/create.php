<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $currentRole = isset($role) ? $role : Session::get('role'); ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>anggota">Data Anggota</a></li>
                <li class="breadcrumb-item active">Tambah Anggota</li>
            </ol>
        </nav>
        <h4>Tambah Anggota</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>anggota/store" method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIS <span class="text-danger">*</span></label>
                    <input type="text" name="nis" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= sanitize($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No Telp <span class="text-danger">*</span></label>
                    <input type="text" name="no_telp" class="form-control" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea name="alamat" class="form-control" rows="3" required></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-1"></i>Username dan password menggunakan NIS secara otomatis.
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="<?= BASE_URL ?>anggota" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
