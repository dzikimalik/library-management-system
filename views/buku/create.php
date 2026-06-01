<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $currentRole = isset($role) ? $role : Session::get('role'); ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>buku">Data Buku</a></li>
                <li class="breadcrumb-item active">Tambah Buku</li>
            </ol>
        </nav>
        <h4>Tambah Buku</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>buku/store" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pengarang <span class="text-danger">*</span></label>
                    <input type="text" name="pengarang" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Penerbit <span class="text-danger">*</span></label>
                    <input type="text" name="penerbit" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">ISBN <span class="text-danger">*</span></label>
                    <input type="text" name="isbn" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" name="tahun" class="form-control" required min="1900" max="2099">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="Referensi">Referensi</option>
                        <option value="Majalah">Majalah</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stok" class="form-control" required min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rak <span class="text-danger">*</span></label>
                    <input type="text" name="rak" class="form-control" required placeholder="R-01">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Cover</label>
                    <input type="file" name="cover" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="<?= BASE_URL ?>buku" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
