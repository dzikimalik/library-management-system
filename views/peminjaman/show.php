<?php $title = 'Detail Peminjaman'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>peminjaman">Peminjaman</a></li><li class="breadcrumb-item active">Detail</li></ol>
        </nav>
        <h4>Detail Peminjaman</h4>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><td style="width:180px"><strong>Anggota</strong></td><td><?= sanitize($detail['nama_anggota'] ?? '-') ?></td></tr>
            <tr><td><strong>Buku</strong></td><td><?= sanitize($detail['judul_buku'] ?? '-') ?></td></tr>
            <tr><td><strong>Petugas</strong></td><td><?= sanitize($detail['nama_petugas'] ?? '-') ?></td></tr>
            <tr><td><strong>Tgl Pinjam</strong></td><td><?= formatDate($detail['tgl_pinjam']) ?></td></tr>
            <tr><td><strong>Jatuh Tempo</strong></td><td><?= formatDate($detail['tgl_jatuh_tempo']) ?></td></tr>
            <tr><td><strong>Tgl Kembali</strong></td><td><?= $detail['tgl_kembali'] ? formatDate($detail['tgl_kembali']) : '-' ?></td></tr>
            <tr><td><strong>Status</strong></td><td>
                <?php
                $s = $detail['status'];
                $badge = ($s === 'dipinjam') ? 'primary' : (($s === 'dikembalikan') ? 'success' : (($s === 'terlambat') ? 'danger' : 'secondary'));
                ?>
                <span class="badge bg-<?= $badge ?>"><?= ucfirst($detail['status']) ?></span>
            </td></tr>
        </table>
        <a href="<?= BASE_URL ?>peminjaman" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
