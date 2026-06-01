<?php $title = 'Tambah Peminjaman'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>peminjaman">Peminjaman</a></li><li class="breadcrumb-item active">Tambah</li></ol>
        </nav>
        <h4>Tambah Peminjaman</h4>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>peminjaman/store" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="anggota_id" class="form-label">Anggota <span class="text-danger">*</span></label>
                <select name="anggota_id" id="anggota_id" class="form-select" required>
                    <option value="">-- Pilih Anggota --</option>
                    <?php foreach ($anggota as $a): ?>
                    <option value="<?= $a['id'] ?>"><?= sanitize($a['nama']) ?> - <?= sanitize($a['nis'] ?? '-') ?> - Kelas: <?= sanitize($a['nama_kelas'] ?? '-') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="buku_id" class="form-label">Buku <span class="text-danger">*</span></label>
                <select name="buku_id" id="buku_id" class="form-select" required>
                    <option value="">-- Pilih Buku --</option>
                    <?php foreach ($buku as $b): ?>
                    <option value="<?= $b['id'] ?>"><?= sanitize($b['judul']) ?> - Stok: <?= $b['stok'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="lama_pinjam" class="form-label">Lama Peminjaman (hari) <span class="text-danger">*</span></label>
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="number" name="lama_pinjam" id="lama_pinjam" class="form-control" value="7" min="1" max="365" required oninput="updateTenggat()">
                    </div>
                    <div class="col-md-8">
                        <small class="text-muted">Tenggat: <span id="tenggatPreview"></span></small>
                    </div>
                </div>
            </div>
            <script>
            function updateTenggat() {
                const hari = parseInt(document.getElementById('lama_pinjam').value) || 7;
                const today = new Date();
                const due = new Date(today);
                due.setDate(due.getDate() + hari);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                document.getElementById('tenggatPreview').textContent = due.toLocaleDateString('id-ID', options);
            }
            updateTenggat();
            </script>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="<?= BASE_URL ?>peminjaman" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Batal</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
