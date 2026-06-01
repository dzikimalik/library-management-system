<?php $title = 'Data Kunjungan'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item active">Data Kunjungan</li></ol>
        </nav>
        <h4>Data Kunjungan</h4>
    </div>
    <a href="<?= BASE_URL ?>kunjungan/create" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengunjung</th>
                        <th>Tanggal</th>
                        <th>Keperluan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($kunjungan as $k): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= sanitize($k['nama_pengunjung'] ?? '-') ?></td>
                        <td><?= isset($k['tgl_kunjungan']) ? formatDate($k['tgl_kunjungan']) : '-' ?></td>
                        <td><?= sanitize($k['keperluan'] ?? '-') ?></td>
                        <td><span class="text-muted">-</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
