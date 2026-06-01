<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="welcome-banner">
    <h5><i class="fas fa-hand-wave me-2"></i>Selamat datang, <?= sanitize(Session::get('nama')) ?>!</h5>
    <p>Anda login sebagai <strong><?= sanitize(Session::get('role')) ?></strong></p>
</div>

<?php if ($role === 'Admin'): ?>
<div class="row g-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value"><?= $total_users ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon success"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div class="stat-label">Total Anggota</div>
                <div class="stat-value">
                    <a href="<?= BASE_URL ?>anggota" class="text-decoration-none text-reset"><?= $total_anggota ?? 0 ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon info"><i class="fas fa-user-tie"></i></div>
            <div>
                <div class="stat-label">Total Pegawai</div>
                <div class="stat-value">
                    <a href="<?= BASE_URL ?>pegawai" class="text-decoration-none text-reset"><?= $total_pegawai ?? 0 ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fas fa-user-shield"></i></div>
            <div>
                <div class="stat-label">Total Kepala</div>
                <div class="stat-value">
                    <a href="<?= BASE_URL ?>kepala" class="text-decoration-none text-reset"><?= $total_kepala ?? 0 ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($role === 'Kepala'): ?>
<div class="row g-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-book"></i></div>
            <div>
                <div class="stat-label">Total Buku</div>
                <div class="stat-value"><?= $total_buku ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="stat-label">Peminjaman Aktif</div>
                <div class="stat-value"><?= $peminjaman_aktif ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon danger"><i class="fas fa-money-bill"></i></div>
            <div>
                <div class="stat-label">Total Denda</div>
                <div class="stat-value"><?= rupiah($total_denda ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon info"><i class="fas fa-calendar"></i></div>
            <div>
                <div class="stat-label">Kunjungan Hari Ini</div>
                <div class="stat-value"><?= $kunjungan_hari_ini ?? 0 ?></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($role === 'Pegawai'): ?>
<div class="row g-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-book"></i></div>
            <div>
                <div class="stat-label">Total Buku</div>
                <div class="stat-value"><?= $total_buku ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon success"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-label">Total Anggota</div>
                <div class="stat-value"><?= $total_anggota ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="stat-label">Peminjaman Aktif</div>
                <div class="stat-value"><?= $peminjaman_aktif ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon info"><i class="fas fa-undo"></i></div>
            <div>
                <div class="stat-label">Pengembalian Hari Ini</div>
                <div class="stat-value"><?= $pengembalian_hari_ini ?? 0 ?></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($role === 'Anggota'): ?>
<div class="row g-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-book"></i></div>
            <div>
                <div class="stat-label">Total Buku</div>
                <div class="stat-value"><?= $total_buku ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="stat-label">Peminjaman Saya</div>
                <div class="stat-value"><?= is_array($peminjaman_saya) ? count($peminjaman_saya) : 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon danger"><i class="fas fa-money-bill"></i></div>
            <div>
                <div class="stat-label">Denda Saya</div>
                <div class="stat-value"><?= is_array($denda_saya) ? rupiah(array_sum(array_column($denda_saya, 'jumlah_denda'))) : rupiah(0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon info"><i class="fas fa-calendar"></i></div>
            <div>
                <div class="stat-label">Kunjungan Hari Ini</div>
                <div class="stat-value"><?= $kunjungan_hari_ini ?? 0 ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <i class="fas fa-chart-bar me-1"></i>Grafik Kunjungan Perpustakaan (7 Hari Terakhir)
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="kunjunganChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('kunjunganChart');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');

    <?php
    $kunjungan_per_hari = $kunjungan_per_hari ?? [];
    $chartLabels = [];
    $chartData = [];
    $dayMap = [];

    foreach ($kunjungan_per_hari as $k) {
        $dayMap[$k['tanggal']] = (int)$k['total'];
    }

    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $label = date('d/m', strtotime($date));
        $chartLabels[] = $label;
        $chartData[] = $dayMap[$date] ?? 0;
    }
    ?>

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: <?= json_encode($chartData) ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
