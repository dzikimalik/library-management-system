<?php
require_once __DIR__ . '/Session.php';

class Middleware
{
    private static $rolePages = [
        'Admin' => ['dashboard', 'user', 'anggota', 'pegawai', 'kepala'],
        'Kepala' => ['dashboard', 'buku', 'peminjaman', 'pengembalian', 'denda', 'kunjungan', 'laporan', 'kelas'],
        'Pegawai' => ['dashboard', 'buku', 'peminjaman', 'pengembalian', 'denda'],
        'Anggota' => ['dashboard', 'buku', 'kunjungan'],
    ];

    public static function checkAccess($role, $page)
    {
        if (!isset(self::$rolePages[$role])) {
            return false;
        }

        $allowed = self::$rolePages[$role];
        if (empty($allowed)) {
            return true;
        }

        foreach ($allowed as $prefix) {
            if (strpos($page, $prefix) === 0) {
                return true;
            }
        }

        return false;
    }

    public static function isLoggedIn()
    {
        return Session::has('user_id') && Session::has('role');
    }

    public static function hasRole($roles)
    {
        if (!self::isLoggedIn()) {
            return false;
        }

        $userRole = Session::get('role');

        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }

        return $userRole === $roles;
    }
}
