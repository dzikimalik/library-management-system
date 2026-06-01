<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class PeminjamanController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'peminjaman')) {
            redirect(BASE_URL);
        }

        $peminjamanModel = $this->model('PeminjamanModel');

        if ($role === 'Anggota') {
            $anggotaModel = $this->model('AnggotaModel');
            $anggota = $anggotaModel->getByUserId(Session::get('user_id'));
            if ($anggota) {
                $data['peminjaman'] = $peminjamanModel->getByAnggota($anggota['id']);
            } else {
                $data['peminjaman'] = [];
            }
        } else {
            $data['peminjaman'] = $peminjamanModel->getAllWithRelation();
        }

        $this->view('peminjaman/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'peminjaman')) {
            redirect(BASE_URL);
        }

        if ($role === 'Anggota') {
            set_flash('error', 'Akses ditolak');
            redirect(BASE_URL . 'peminjaman');
        }

        $anggotaModel = $this->model('AnggotaModel');
        $bukuModel = $this->model('BukuModel');

        $data['anggota'] = $anggotaModel->getAllWithKelas();
        $data['buku'] = $bukuModel->getAvailable();

        $this->view('peminjaman/create', $data);
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'peminjaman') || $role === 'Anggota') {
            redirect(BASE_URL . 'peminjaman');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'peminjaman/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'peminjaman/create');
        }

        $rules = [
            'anggota_id' => 'required|numeric',
            'buku_id' => 'required|numeric',
            'lama_pinjam' => 'required|numeric|min:1'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'peminjaman/create');
        }

        $anggotaId = (int)$_POST['anggota_id'];
        $bukuId = (int)$_POST['buku_id'];
        $lamaPinjam = max(1, (int)$_POST['lama_pinjam']);

        $anggotaModel = $this->model('AnggotaModel');
        $anggota = $anggotaModel->find($anggotaId);

        if (!$anggota) {
            set_flash('error', 'Anggota tidak ditemukan');
            redirect(BASE_URL . 'peminjaman/create');
        }

        $bukuModel = $this->model('BukuModel');
        $buku = $bukuModel->find($bukuId);

        if (!$buku) {
            set_flash('error', 'Buku tidak ditemukan');
            redirect(BASE_URL . 'peminjaman/create');
        }

        if ($buku['stok'] <= 0) {
            set_flash('error', 'Stok buku habis');
            redirect(BASE_URL . 'peminjaman/create');
        }

        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $peminjamanData = [
                'anggota_id' => $anggotaId,
                'buku_id' => $bukuId,
                'user_id' => Session::get('user_id'),
                'tgl_pinjam' => date('Y-m-d'),
                'tgl_jatuh_tempo' => date('Y-m-d', strtotime("+{$lamaPinjam} days")),
                'status' => 'dipinjam'
            ];

            $peminjamanModel = $this->model('PeminjamanModel');
            $peminjamanModel->create($peminjamanData);

            $bukuModel->updateStok($bukuId, -1);

            $db->commit();

            set_flash('success', 'Peminjaman berhasil diproses');
            redirect(BASE_URL . 'peminjaman');
        } catch (Exception $e) {
            $db->rollBack();
            set_flash('error', 'Gagal memproses peminjaman');
            redirect(BASE_URL . 'peminjaman/create');
        }
    }

    public function show($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'peminjaman')) {
            redirect(BASE_URL);
        }

        $peminjamanModel = $this->model('PeminjamanModel');

        if ($role === 'Anggota') {
            $data['peminjaman'] = $peminjamanModel->getAllWithRelation();
            $peminjaman = [];
            $anggotaModel = $this->model('AnggotaModel');
            $anggota = $anggotaModel->getByUserId(Session::get('user_id'));
            if ($anggota) {
                foreach ($data['peminjaman'] as $p) {
                    if ($p['id'] == $id && $p['anggota_id'] == $anggota['id']) {
                        $peminjaman = $p;
                        break;
                    }
                }
            }
            if (empty($peminjaman)) {
                set_flash('error', 'Data tidak ditemukan');
                redirect(BASE_URL . 'peminjaman');
            }
            $data['detail'] = $peminjaman;
        } else {
            $all = $peminjamanModel->getAllWithRelation();
            $found = null;
            foreach ($all as $p) {
                if ($p['id'] == $id) {
                    $found = $p;
                    break;
                }
            }
            if (!$found) {
                set_flash('error', 'Peminjaman tidak ditemukan');
                redirect(BASE_URL . 'peminjaman');
            }
            $data['detail'] = $found;
        }

        $this->view('peminjaman/show', $data);
    }

    public function delete($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'peminjaman') || $role === 'Anggota') {
            redirect(BASE_URL . 'peminjaman');
        }

        $peminjamanModel = $this->model('PeminjamanModel');
        $peminjaman = $peminjamanModel->find($id);

        if (!$peminjaman) {
            set_flash('error', 'Peminjaman tidak ditemukan');
            redirect(BASE_URL . 'peminjaman');
        }

        if ($peminjaman['status'] !== 'dipinjam') {
            set_flash('error', 'Hanya peminjaman dengan status dipinjam yang dapat dibatalkan');
            redirect(BASE_URL . 'peminjaman');
        }

        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $bukuModel = $this->model('BukuModel');
            $bukuModel->updateStok($peminjaman['buku_id'], 1);

            $peminjamanModel->delete($id);

            $db->commit();

            set_flash('success', 'Peminjaman berhasil dibatalkan');
            redirect(BASE_URL . 'peminjaman');
        } catch (Exception $e) {
            $db->rollBack();
            set_flash('error', 'Gagal membatalkan peminjaman');
            redirect(BASE_URL . 'peminjaman');
        }
    }
}
