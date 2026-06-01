<?php $title = 'Data Denda'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $currentRole = isset($role) ? $role : Session::get('role'); ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item active">Data Denda</li></ol>
        </nav>
        <h4>Data Denda</h4>
    </div>
    <?php if ($currentRole !== 'Anggota'): ?>
    <a href="<?= BASE_URL ?>denda/create" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah Denda
    </a>
    <?php endif; ?>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Jumlah Denda</th>
                        <th>Status</th>
                        <th>Tgl Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($denda as $d): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= sanitize($d['nama_anggota'] ?? '-') ?></td>
                        <td><?= sanitize($d['judul_buku'] ?? '-') ?></td>
                        <td><?= rupiah($d['jumlah_denda']) ?></td>
                        <td>
                            <?php if ($d['status_bayar'] === 'lunas'): ?>
                            <span class="badge bg-success">Lunas</span>
                            <?php else: ?>
                            <span class="badge bg-warning text-dark">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $d['tgl_bayar'] ? formatDate($d['tgl_bayar']) : '-' ?></td>
                        <td>
                            <?php if ($d['status_bayar'] === 'belum' && $currentRole !== 'Anggota'): ?>
                            <a href="<?= BASE_URL ?>denda/bayar/<?= $d['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Konfirmasi pembayaran denda?')"><i class="fas fa-credit-card me-1"></i>Bayar</a>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
