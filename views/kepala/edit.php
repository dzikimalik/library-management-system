<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $k = $kepala; ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>kepala">Data Kepala</a></li>
                <li class="breadcrumb-item active">Edit Kepala</li>
            </ol>
        </nav>
        <h4>Edit Kepala</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>kepala/update/<?= $k['id'] ?>" method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?= sanitize($k['nama']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" class="form-control" required value="<?= sanitize($k['nip']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" value="<?= isset($user['username']) ? sanitize($user['username']) : '' ?>" readonly disabled>
                    <small class="text-muted">Username tidak dapat diubah</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Akun</label>
                    <select name="status" class="form-select">
                        <option value="aktif" <?= isset($user['status']) && $user['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= isset($user['status']) && $user['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="<?= BASE_URL ?>kepala" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
