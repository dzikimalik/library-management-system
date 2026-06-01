<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class KunjunganController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kunjungan')) {
            redirect(BASE_URL);
        }

        $kunjunganModel = $this->model('KunjunganModel');

        if ($role === 'Anggota') {
            $anggotaModel = $this->model('AnggotaModel');
            $anggota = $anggotaModel->getByUserId(Session::get('user_id'));
            $all = $kunjunganModel->getAllWithRelation();
            $data['kunjungan'] = [];
            if ($anggota) {
                foreach ($all as $k) {
                    if ($k['anggota_id'] == $anggota['id']) {
                        $data['kunjungan'][] = $k;
                    }
                }
            }
        } else {
            $data['kunjungan'] = $kunjunganModel->getAllWithRelation();
        }

        $this->view('kunjungan/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kunjungan')) {
            redirect(BASE_URL);
        }

        $data['role'] = $role;

        if ($role === 'Anggota') {
            $anggotaModel = $this->model('AnggotaModel');
            $anggota = $anggotaModel->getByUserId(Session::get('user_id'));
            $data['anggota'] = $anggota;
        }

        $this->view('kunjungan/create', $data);
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kunjungan')) {
            redirect(BASE_URL . 'kunjungan');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'kunjungan/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'kunjungan/create');
        }

        $rules = [
            'keperluan' => 'required'
        ];

        if ($role !== 'Anggota') {
            $rules['nama_pengunjung'] = 'required';
        }

        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi');
            redirect(BASE_URL . 'kunjungan/create');
        }

        $data = [
            'tgl_kunjungan' => date('Y-m-d'),
            'keperluan' => sanitize($_POST['keperluan'])
        ];

        if ($role === 'Anggota') {
            $anggotaModel = $this->model('AnggotaModel');
            $anggota = $anggotaModel->getByUserId(Session::get('user_id'));
            if ($anggota) {
                $data['anggota_id'] = $anggota['id'];
                $data['nama_pengunjung'] = $anggota['nama'];
            } else {
                set_flash('error', 'Data anggota tidak ditemukan');
                redirect(BASE_URL . 'kunjungan/create');
            }
        } else {
            $data['anggota_id'] = null;
            $data['nama_pengunjung'] = sanitize($_POST['nama_pengunjung']);
        }

        $kunjunganModel = $this->model('KunjunganModel');
        $kunjunganModel->create($data);

        set_flash('success', 'Kunjungan berhasil dicatat');
        redirect(BASE_URL . 'kunjungan');
    }
}
