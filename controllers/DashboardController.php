<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class DashboardController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'dashboard')) {
            redirect(BASE_URL);
        }

        $data['role'] = $role;

        $bukuModel = $this->model('BukuModel');
        $anggotaModel = $this->model('AnggotaModel');
        $peminjamanModel = $this->model('PeminjamanModel');
        $userModel = $this->model('UserModel');
        $roleModel = $this->model('RoleModel');

        if ($role === 'Admin') {
            $data['total_users'] = $userModel->count();
            $data['total_roles'] = $roleModel->count();
            $data['total_anggota'] = $anggotaModel->count();
            $pegawaiModel = $this->model('PegawaiModel');
            $data['total_pegawai'] = $pegawaiModel->count();
            $kepalaModel = $this->model('KepalaModel');
            $data['total_kepala'] = $kepalaModel->count();
        } elseif ($role === 'Kepala') {
            $data['total_buku'] = $bukuModel->count();
            $data['peminjaman_aktif'] = $peminjamanModel->countActive();

            $dendaModel = $this->model('DendaModel');
            $data['total_denda'] = $dendaModel->sumUnpaid();

            $kunjunganModel = $this->model('KunjunganModel');
            $data['kunjungan_hari_ini'] = $kunjunganModel->countToday();
        } elseif ($role === 'Pegawai') {
            $data['total_buku'] = $bukuModel->count();
            $data['total_anggota'] = $anggotaModel->count();
            $data['peminjaman_aktif'] = $peminjamanModel->countActive();

            $pengembalianModel = $this->model('PengembalianModel');
            $data['pengembalian_hari_ini'] = $pengembalianModel->countToday();
        } elseif ($role === 'Anggota') {
            $data['total_buku'] = $bukuModel->count();

            $anggota = $anggotaModel->getByUserId(Session::get('user_id'));
            if ($anggota) {
                $data['peminjaman_saya'] = $peminjamanModel->getByAnggota($anggota['id']);
                $dendaModel = $this->model('DendaModel');
                $data['denda_saya'] = $dendaModel->getByAnggota($anggota['id']);
            } else {
                $data['peminjaman_saya'] = [];
                $data['denda_saya'] = [];
            }

            $kunjunganModel = $this->model('KunjunganModel');
            $data['kunjungan_hari_ini'] = $kunjunganModel->countToday();
            $data['total_kunjungan'] = $kunjunganModel->countAll();
            $data['kunjungan_per_hari'] = $kunjunganModel->countPerDay(7);
        }

        $this->view('dashboard/index', $data);
    }
}
