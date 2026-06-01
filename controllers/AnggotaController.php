<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class AnggotaController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'anggota')) {
            redirect(BASE_URL);
        }

        $anggotaModel = $this->model('AnggotaModel');
        $data['anggota'] = $anggotaModel->getAllWithKelas();

        $this->view('anggota/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'anggota')) {
            redirect(BASE_URL);
        }

        if ($role === 'Anggota') {
            set_flash('error', 'Akses ditolak');
            redirect(BASE_URL . 'anggota');
        }

        $kelasModel = $this->model('KelasModel');
        $data['kelas'] = $kelasModel->all();

        $this->view('anggota/create', $data);
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'anggota') || $role === 'Anggota') {
            redirect(BASE_URL . 'anggota');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'anggota/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'anggota/create');
        }

        $rules = [
            'nama' => 'required',
            'nis' => 'required',
            'kelas_id' => 'required|numeric',
            'no_telp' => 'required',
            'alamat' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'anggota/create');
        }

        $nis = sanitize($_POST['nis']);

        $anggotaModel = $this->model('AnggotaModel');
        $existingAnggota = $anggotaModel->getByNis($nis);
        if ($existingAnggota) {
            set_flash('error', 'NIS sudah digunakan');
            redirect(BASE_URL . 'anggota/create');
        }

        $userModel = $this->model('UserModel');
        $existingUser = $userModel->getByUsername($nis);
        if ($existingUser) {
            set_flash('error', 'NIS sudah terdaftar sebagai user');
            redirect(BASE_URL . 'anggota/create');
        }

        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $userData = [
                'username' => $nis,
                'password' => password_hash($nis, PASSWORD_BCRYPT),
                'role_id' => 4,
                'status' => 'aktif'
            ];
            $userId = $userModel->create($userData);

            $anggotaData = [
                'user_id' => $userId,
                'nama' => sanitize($_POST['nama']),
                'nis' => $nis,
                'kelas_id' => (int)$_POST['kelas_id'],
                'no_telp' => sanitize($_POST['no_telp']),
                'alamat' => sanitize($_POST['alamat'])
            ];

            $anggotaModel = $this->model('AnggotaModel');
            $anggotaModel->create($anggotaData);

            $db->commit();
            set_flash('success', 'Anggota berhasil ditambahkan');
            redirect(BASE_URL . 'anggota');
        } catch (Exception $e) {
            $db->rollBack();
            set_flash('error', 'Gagal menambahkan anggota: ' . $e->getMessage());
            redirect(BASE_URL . 'anggota/create');
        }
    }

    public function edit($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'anggota')) {
            redirect(BASE_URL);
        }

        if ($role === 'Anggota') {
            set_flash('error', 'Akses ditolak');
            redirect(BASE_URL . 'anggota');
        }

        $anggotaModel = $this->model('AnggotaModel');
        $anggota = $anggotaModel->findWithRelation($id);

        if (!$anggota) {
            set_flash('error', 'Anggota tidak ditemukan');
            redirect(BASE_URL . 'anggota');
        }

        $kelasModel = $this->model('KelasModel');
        $data['anggota'] = $anggota;
        $data['kelas'] = $kelasModel->all();

        $this->view('anggota/edit', $data);
    }

    public function update($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'anggota') || $role === 'Anggota') {
            redirect(BASE_URL . 'anggota');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'anggota/edit/' . $id);
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'anggota/edit/' . $id);
        }

        $rules = [
            'nama' => 'required',
            'nis' => 'required',
            'kelas_id' => 'required|numeric',
            'no_telp' => 'required',
            'alamat' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'anggota/edit/' . $id);
        }

        $anggotaModel = $this->model('AnggotaModel');
        $anggota = $anggotaModel->find($id);

        if (!$anggota) {
            set_flash('error', 'Anggota tidak ditemukan');
            redirect(BASE_URL . 'anggota');
        }

        $nis = sanitize($_POST['nis']);

        $existingAnggota = $anggotaModel->getByNis($nis);
        if ($existingAnggota && (int)$existingAnggota['id'] !== (int)$id) {
            set_flash('error', 'NIS sudah digunakan oleh anggota lain');
            redirect(BASE_URL . 'anggota/edit/' . $id);
        }

        $data = [
            'nama' => sanitize($_POST['nama']),
            'nis' => $nis,
            'kelas_id' => (int)$_POST['kelas_id'],
            'no_telp' => sanitize($_POST['no_telp']),
            'alamat' => sanitize($_POST['alamat'])
        ];

        $anggotaModel->update($id, $data);

        if (!empty($_POST['password'])) {
            $userModel = $this->model('UserModel');
            $userModel->update($anggota['user_id'], [
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
            ]);
        }

        set_flash('success', 'Anggota berhasil diperbarui');
        redirect(BASE_URL . 'anggota');
    }

    public function delete($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'anggota') || $role === 'Anggota') {
            redirect(BASE_URL . 'anggota');
        }

        $anggotaModel = $this->model('AnggotaModel');
        $anggota = $anggotaModel->find($id);

        if (!$anggota) {
            set_flash('error', 'Anggota tidak ditemukan');
            redirect(BASE_URL . 'anggota');
        }

        $userModel = $this->model('UserModel');
        $userModel->delete($anggota['user_id']);

        set_flash('success', 'Anggota berhasil dihapus');
        redirect(BASE_URL . 'anggota');
    }
}
