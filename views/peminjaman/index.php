<?php $title = 'Data Peminjaman'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $currentRole = isset($role) ? $role : Session::get('role'); ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item active">Data Peminjaman</li></ol>
        </nav>
        <h4>Data Peminjaman</h4>
    </div>
    <?php if ($currentRole !== 'Anggota'): ?>
    <a href="<?= BASE_URL ?>peminjaman/create" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah</a>
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
                        <th>Petugas</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($peminjaman as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= sanitize($p['nama_anggota'] ?? '-') ?></td>
                        <td><?= sanitize($p['judul_buku'] ?? '-') ?></td>
                        <td><?= sanitize($p['nama_petugas'] ?? '-') ?></td>
                        <td><?= formatDate($p['tgl_pinjam']) ?></td>
                        <td><?= formatDate($p['tgl_jatuh_tempo']) ?></td>
                        <td><?= $p['tgl_kembali'] ? formatDate($p['tgl_kembali']) : '-' ?></td>
                        <td>
                            <?php
                            $s = $p['status'];
                            $badge = ($s === 'dipinjam') ? 'primary' : (($s === 'dikembalikan') ? 'success' : (($s === 'terlambat') ? 'danger' : 'secondary'));
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= ucfirst($p['status']) ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>peminjaman/show/<?= $p['id'] ?>" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            <?php if ($p['status'] === 'dipinjam' && $currentRole !== 'Anggota'): ?>
                            <a href="<?= BASE_URL ?>peminjaman/delete/<?= $p['id'] ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></a>
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
