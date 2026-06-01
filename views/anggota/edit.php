<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $currentRole = isset($role) ? $role : Session::get('role'); ?>
<?php $a = $anggota; ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>anggota">Data Anggota</a></li>
                <li class="breadcrumb-item active">Edit Anggota</li>
            </ol>
        </nav>
        <h4>Edit Anggota</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>anggota/update/<?= $a['id'] ?>" method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?= sanitize($a['nama']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIS <span class="text-danger">*</span></label>
                    <input type="text" name="nis" class="form-control" required value="<?= sanitize($a['nis']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= (int)$a['kelas_id'] === (int)$k['id'] ? 'selected' : '' ?>><?= sanitize($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No Telp <span class="text-danger">*</span></label>
                    <input type="text" name="no_telp" class="form-control" required value="<?= sanitize($a['no_telp']) ?>">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea name="alamat" class="form-control" rows="3" required><?= sanitize($a['alamat']) ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password <small class="text-muted">(opsional)</small></label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password. Default: NIS</small>
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
