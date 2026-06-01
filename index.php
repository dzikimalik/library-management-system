<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/core/Session.php';
Session::start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/app.php';

spl_autoload_register(function ($class) {
    $directories = [
        __DIR__ . '/core/',
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
    ];

    foreach ($directories as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Helper.php';

// Auth routes
Router::add('GET', 'login', 'AuthController', 'login');
Router::add('POST', 'login', 'AuthController', 'processLogin');
Router::add('GET', 'logout', 'AuthController', 'logout');

// Dashboard
Router::add('GET', 'dashboard', 'DashboardController', 'index');

// Buku
Router::add('GET', 'buku', 'BukuController', 'index');
Router::add('GET', 'buku/create', 'BukuController', 'create');
Router::add('POST', 'buku/store', 'BukuController', 'store');
Router::add('GET', 'buku/edit/{id}', 'BukuController', 'edit');
Router::add('POST', 'buku/update/{id}', 'BukuController', 'update');
Router::add('GET', 'buku/delete/{id}', 'BukuController', 'delete');

// Anggota
Router::add('GET', 'anggota', 'AnggotaController', 'index');
Router::add('GET', 'anggota/create', 'AnggotaController', 'create');
Router::add('POST', 'anggota/store', 'AnggotaController', 'store');
Router::add('GET', 'anggota/edit/{id}', 'AnggotaController', 'edit');
Router::add('POST', 'anggota/update/{id}', 'AnggotaController', 'update');
Router::add('GET', 'anggota/delete/{id}', 'AnggotaController', 'delete');

// User
Router::add('GET', 'user', 'UserController', 'index');
Router::add('GET', 'user/create', 'UserController', 'create');
Router::add('POST', 'user/store', 'UserController', 'store');
Router::add('GET', 'user/edit/{id}', 'UserController', 'edit');
Router::add('POST', 'user/update/{id}', 'UserController', 'update');
Router::add('GET', 'user/delete/{id}', 'UserController', 'delete');

// Kelas
Router::add('GET', 'kelas', 'KelasController', 'index');
Router::add('GET', 'kelas/create', 'KelasController', 'create');
Router::add('POST', 'kelas/store', 'KelasController', 'store');
Router::add('GET', 'kelas/edit/{id}', 'KelasController', 'edit');
Router::add('POST', 'kelas/update/{id}', 'KelasController', 'update');
Router::add('GET', 'kelas/delete/{id}', 'KelasController', 'delete');

// Peminjaman
Router::add('GET', 'peminjaman', 'PeminjamanController', 'index');
Router::add('GET', 'peminjaman/create', 'PeminjamanController', 'create');
Router::add('POST', 'peminjaman/store', 'PeminjamanController', 'store');
Router::add('GET', 'peminjaman/show/{id}', 'PeminjamanController', 'show');
Router::add('GET', 'peminjaman/delete/{id}', 'PeminjamanController', 'delete');

// Pengembalian
Router::add('GET', 'pengembalian', 'PengembalianController', 'index');
Router::add('GET', 'pengembalian/create', 'PengembalianController', 'create');
Router::add('POST', 'pengembalian/store', 'PengembalianController', 'store');

// Denda
Router::add('GET', 'denda', 'DendaController', 'index');
Router::add('GET', 'denda/create', 'DendaController', 'create');
Router::add('POST', 'denda/store', 'DendaController', 'store');
Router::add('GET', 'denda/bayar/{id}', 'DendaController', 'bayar');

// Kunjungan
Router::add('GET', 'kunjungan', 'KunjunganController', 'index');
Router::add('GET', 'kunjungan/create', 'KunjunganController', 'create');
Router::add('POST', 'kunjungan/store', 'KunjunganController', 'store');

// Laporan
Router::add('GET', 'laporan', 'LaporanController', 'index');
Router::add('GET', 'laporan/peminjaman', 'LaporanController', 'peminjaman');
Router::add('GET', 'laporan/pengembalian', 'LaporanController', 'pengembalian');
Router::add('GET', 'laporan/kunjungan', 'LaporanController', 'kunjungan');
Router::add('GET', 'laporan/denda', 'LaporanController', 'denda');

// Pegawai
Router::add('GET', 'pegawai', 'PegawaiController', 'index');
Router::add('GET', 'pegawai/create', 'PegawaiController', 'create');
Router::add('POST', 'pegawai/store', 'PegawaiController', 'store');
Router::add('GET', 'pegawai/edit/{id}', 'PegawaiController', 'edit');
Router::add('POST', 'pegawai/update/{id}', 'PegawaiController', 'update');
Router::add('GET', 'pegawai/delete/{id}', 'PegawaiController', 'delete');

// Kepala
Router::add('GET', 'kepala', 'KepalaController', 'index');
Router::add('GET', 'kepala/create', 'KepalaController', 'create');
Router::add('POST', 'kepala/store', 'KepalaController', 'store');
Router::add('GET', 'kepala/edit/{id}', 'KepalaController', 'edit');
Router::add('POST', 'kepala/update/{id}', 'KepalaController', 'update');
Router::add('GET', 'kepala/delete/{id}', 'KepalaController', 'delete');

// Default redirect to login
Router::add('GET', '', 'AuthController', 'login');

Router::run();
ob_end_flush();
