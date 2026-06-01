<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class DendaController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'denda')) {
            redirect(BASE_URL);
        }

        if ($role === 'Anggota') {
            $anggotaModel = $this->model('AnggotaModel');
            $anggota = $anggotaModel->getByUserId(Session::get('user_id'));
            if ($anggota) {
                $dendaModel = $this->model('DendaModel');
                $data['denda'] = $dendaModel->getByAnggota($anggota['id']);
            } else {
                $data['denda'] = [];
            }
        } else {
            $dendaModel = $this->model('DendaModel');
            $data['denda'] = $dendaModel->getAllWithRelation();
        }

        $this->view('denda/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'denda') || $role === 'Anggota') {
            redirect(BASE_URL . 'denda');
        }

        $pengembalianModel = $this->model('PengembalianModel');
        $data['pengembalian'] = $pengembalianModel->getAllWithRelation();

        $this->view('denda/create', $data);
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'denda') || $role === 'Anggota') {
            redirect(BASE_URL . 'denda');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'denda/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'denda/create');
        }

        $rules = [
            'pengembalian_id' => 'required|numeric',
            'jumlah_denda' => 'required|numeric'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'denda/create');
        }

        $dendaModel = $this->model('DendaModel');
        $dendaModel->create([
            'pengembalian_id' => (int)$_POST['pengembalian_id'],
            'jumlah_denda' => str_replace('.', '', $_POST['jumlah_denda']),
            'status_bayar' => 'belum',
            'tgl_bayar' => null
        ]);

        set_flash('success', 'Denda berhasil ditambahkan');
        redirect(BASE_URL . 'denda');
    }

    public function bayar($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'denda') || $role === 'Anggota') {
            redirect(BASE_URL . 'denda');
        }

        $dendaModel = $this->model('DendaModel');
        $denda = $dendaModel->find($id);

        if (!$denda) {
            set_flash('error', 'Denda tidak ditemukan');
            redirect(BASE_URL . 'denda');
        }

        if ($denda['status_bayar'] === 'lunas') {
            set_flash('error', 'Denda sudah lunas');
            redirect(BASE_URL . 'denda');
        }

        $dendaModel->update($id, [
            'status_bayar' => 'lunas',
            'tgl_bayar' => date('Y-m-d')
        ]);

        set_flash('success', 'Pembayaran denda berhasil dicatat');
        redirect(BASE_URL . 'denda');
    }
}
