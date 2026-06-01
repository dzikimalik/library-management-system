<?php
$role = Session::get('role');
$currentUrl = $_SERVER['REQUEST_URI'];
$baseUrl = BASE_URL;
function isActive($segment) {
    global $currentUrl, $baseUrl;
    $url = str_replace($baseUrl, '', $currentUrl);
    return strpos($url, $segment) === 0 ? 'active' : '';
}
?>
<div class="sidebar" id="sidebarMenu">
    <div class="sidebar-heading">Menu</div>
    <nav class="nav flex-column">
        <a class="nav-link <?= isActive('dashboard') ?>" href="<?= BASE_URL ?>dashboard">
            <i class="fas fa-tachometer-alt"></i>Dashboard
        </a>

        <?php if (Middleware::checkAccess($role, 'buku')): ?>
        <a class="nav-link <?= isActive('buku') ?>" href="<?= BASE_URL ?>buku">
            <i class="fas fa-book"></i>Buku
        </a>
        <?php endif; ?>

        <?php if (Middleware::checkAccess($role, 'anggota')): ?>
        <a class="nav-link <?= isActive('anggota') ?>" href="<?= BASE_URL ?>anggota">
            <i class="fas fa-users"></i>Anggota
        </a>
        <?php endif; ?>

        <?php if (Middleware::checkAccess($role, 'peminjaman')): ?>
        <a class="nav-link <?= isActive('peminjaman') ?>" href="<?= BASE_URL ?>peminjaman">
            <i class="fas fa-hand-holding"></i>Peminjaman
        </a>
        <?php endif; ?>

        <?php if (Middleware::checkAccess($role, 'pengembalian')): ?>
        <a class="nav-link <?= isActive('pengembalian') ?>" href="<?= BASE_URL ?>pengembalian">
            <i class="fas fa-undo-alt"></i>Pengembalian
        </a>
        <?php endif; ?>

        <?php if (Middleware::checkAccess($role, 'denda')): ?>
        <a class="nav-link <?= isActive('denda') ?>" href="<?= BASE_URL ?>denda">
            <i class="fas fa-money-bill-wave"></i>Denda
        </a>
        <?php endif; ?>

        <?php if (Middleware::checkAccess($role, 'kunjungan')): ?>
        <a class="nav-link <?= isActive('kunjungan') ?>" href="<?= BASE_URL ?>kunjungan">
            <i class="fas fa-clipboard-list"></i>Kunjungan
        </a>
        <?php endif; ?>

        <?php if (Middleware::checkAccess($role, 'laporan')): ?>
        <a class="nav-link <?= isActive('laporan') ?>" href="<?= BASE_URL ?>laporan">
            <i class="fas fa-print"></i>Laporan
        </a>
        <?php endif; ?>
    </nav>
</div>
