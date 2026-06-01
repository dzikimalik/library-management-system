<?php $title = 'Data Pengembalian'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item active">Data Pengembalian</li></ol>
        </nav>
        <h4>Data Pengembalian</h4>
    </div>
    <a href="<?= BASE_URL ?>pengembalian/create" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah</a>
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
                        <th>Petugas</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($pengembalian as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= sanitize($p['nama_anggota'] ?? '-') ?></td>
                        <td><?= sanitize($p['judul_buku'] ?? '-') ?></td>
                        <td><?= sanitize($p['nama_petugas'] ?? '-') ?></td>
                        <td><?= isset($p['tgl_pinjam']) ? formatDate($p['tgl_pinjam']) : '-' ?></td>
                        <td><?= formatDate($p['tgl_kembali']) ?></td>
                        <td><?= rupiah($p['denda'] ?? 0) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>peminjaman/show/<?= $p['peminjaman_id'] ?? 0 ?>" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
