<?php $title = 'Laporan'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li><li class="breadcrumb-item active">Laporan</li></ol>
        </nav>
        <h4>Laporan</h4>
    </div>
</div>
<?php
$startDefault = date('Y-m-01');
$endDefault = date('Y-m-d');
$reports = [
    ['icon' => 'fas fa-hand-holding', 'color' => 'primary', 'title' => 'Laporan Peminjaman', 'desc' => 'Cetak laporan data peminjaman buku per periode.', 'route' => 'peminjaman'],
    ['icon' => 'fas fa-undo-alt', 'color' => 'success', 'title' => 'Laporan Pengembalian', 'desc' => 'Cetak laporan data pengembalian buku per periode.', 'route' => 'pengembalian'],
    ['icon' => 'fas fa-clipboard-list', 'color' => 'info', 'title' => 'Laporan Kunjungan', 'desc' => 'Cetak laporan data kunjungan perpustakaan per periode.', 'route' => 'kunjungan'],
    ['icon' => 'fas fa-money-bill-wave', 'color' => 'warning', 'title' => 'Laporan Denda', 'desc' => 'Cetak laporan data denda perpustakaan per periode.', 'route' => 'denda']
];
?>
<div class="row g-4">
    <?php foreach ($reports as $report): ?>
    <div class="col-md-6">
        <div class="card h-100 border-start border-4 border-<?= $report['color'] ?>">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="p-3 rounded-3 bg-<?= $report['color'] ?> bg-opacity-10 text-<?= $report['color'] ?>">
                        <i class="<?= $report['icon'] ?> fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1"><?= $report['title'] ?></h5>
                        <p class="card-text text-muted small"><?= $report['desc'] ?></p>
                    </div>
                </div>
                <form class="row g-2" action="<?= BASE_URL ?>laporan/<?= $report['route'] ?>" method="GET" target="_blank">
                    <div class="col-md-5">
                        <label class="form-label small">Tanggal Awal</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="<?= $startDefault ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="<?= $endDefault ?>" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-<?= $report['color'] ?> btn-sm w-100"><i class="fas fa-print me-1"></i>Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
