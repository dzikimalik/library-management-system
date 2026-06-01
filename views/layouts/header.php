<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>

<?php
$currentRole = Session::get('role');
$currentNama = Session::get('nama');
$initial = strtoupper(substr($currentNama, 0, 1));
$currentUrl = $_SERVER['REQUEST_URI'];
$basePath = parse_url(BASE_URL, PHP_URL_PATH);
$urlPath = parse_url($currentUrl, PHP_URL_PATH);
$urlSegment = trim(str_replace($basePath, '', $urlPath), '/');
$urlSegment = strtok($urlSegment, '/');
?>
<!-- TOPBAR -->
<nav class="topbar">
    <button class="btn btn-link d-lg-none text-dark p-0 me-3" type="button" id="sidebarToggle" style="font-size:1.2rem;">
        <i class="fas fa-bars"></i>
    </button>
    <a href="<?= BASE_URL ?>dashboard" class="brand">
        <i class="fas fa-book-open"></i><?= APP_NAME ?>
    </a>
    <div class="topbar-right">
        <div class="user-info">
            <div class="avatar"><?= $initial ?></div>
            <div>
                <div style="font-weight:600;color:var(--dark);line-height:1.2;"><?= sanitize($currentNama) ?></div>
                <small><?= $currentRole ?></small>
            </div>
        </div>
        <a href="<?= BASE_URL ?>logout" class="btn btn-outline-secondary btn-sm" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</nav>

<!-- SIDEBAR OVERLAY (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-heading">Menu Utama</div>

    <a class="nav-link <?= $urlSegment === 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>dashboard">
        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
    </a>

    <?php if (Middleware::checkAccess($currentRole, 'buku')): ?>
    <a class="nav-link <?= $urlSegment === 'buku' ? 'active' : '' ?>" href="<?= BASE_URL ?>buku">
        <i class="fas fa-book"></i><span>Buku</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'user')): ?>
    <a class="nav-link <?= $urlSegment === 'user' ? 'active' : '' ?>" href="<?= BASE_URL ?>user">
        <i class="fas fa-user-cog"></i><span>Pengguna</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'anggota')): ?>
    <a class="nav-link <?= $urlSegment === 'anggota' ? 'active' : '' ?>" href="<?= BASE_URL ?>anggota">
        <i class="fas fa-users"></i><span>Anggota</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'pegawai')): ?>
    <a class="nav-link <?= $urlSegment === 'pegawai' ? 'active' : '' ?>" href="<?= BASE_URL ?>pegawai">
        <i class="fas fa-user-tie"></i><span>Pegawai</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'kepala')): ?>
    <a class="nav-link <?= $urlSegment === 'kepala' ? 'active' : '' ?>" href="<?= BASE_URL ?>kepala">
        <i class="fas fa-user-shield"></i><span>Kepala</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'kelas')): ?>
    <a class="nav-link <?= $urlSegment === 'kelas' ? 'active' : '' ?>" href="<?= BASE_URL ?>kelas">
        <i class="fas fa-layer-group"></i><span>Kelas</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'peminjaman')): ?>
    <a class="nav-link <?= $urlSegment === 'peminjaman' ? 'active' : '' ?>" href="<?= BASE_URL ?>peminjaman">
        <i class="fas fa-hand-holding"></i><span>Peminjaman</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'pengembalian')): ?>
    <a class="nav-link <?= $urlSegment === 'pengembalian' ? 'active' : '' ?>" href="<?= BASE_URL ?>pengembalian">
        <i class="fas fa-undo-alt"></i><span>Pengembalian</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'denda')): ?>
    <a class="nav-link <?= $urlSegment === 'denda' ? 'active' : '' ?>" href="<?= BASE_URL ?>denda">
        <i class="fas fa-money-bill-wave"></i><span>Denda</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'kunjungan')): ?>
    <a class="nav-link <?= $urlSegment === 'kunjungan' ? 'active' : '' ?>" href="<?= BASE_URL ?>kunjungan">
        <i class="fas fa-clipboard-list"></i><span>Kunjungan</span>
    </a>
    <?php endif; ?>

    <?php if (Middleware::checkAccess($currentRole, 'laporan')): ?>
    <div class="sidebar-divider"></div>
    <div class="sidebar-heading">Laporan</div>
    <a class="nav-link <?= $urlSegment === 'laporan' ? 'active' : '' ?>" href="<?= BASE_URL ?>laporan">
        <i class="fas fa-print"></i><span>Laporan</span>
    </a>
    <?php endif; ?>

    <div class="sidebar-divider"></div>
    <a class="nav-link" href="<?= BASE_URL ?>logout" style="color:rgba(255,255,255,.4);">
        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
    </a>
</div>

<div id="content-wrapper">
