<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class PegawaiController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pegawai')) {
            redirect(BASE_URL);
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT pegawai.*, users.username, users.status
            FROM pegawai
            JOIN users ON users.id = pegawai.user_id
            ORDER BY pegawai.id DESC
        ");
        $data['pegawai'] = $stmt->fetchAll();

        $this->view('pegawai/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pegawai')) {
            redirect(BASE_URL);
        }

        $this->view('pegawai/create');
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pegawai')) {
            redirect(BASE_URL . 'pegawai');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'pegawai/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'pegawai/create');
        }

        $rules = [
            'nama' => 'required',
            'nip' => 'required',
            'username' => 'required',
            'password' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'pegawai/create');
        }

        $userModel = $this->model('UserModel');
        $existingUser = $userModel->getByUsername(sanitize($_POST['username']));
        if ($existingUser) {
            set_flash('error', 'Username sudah digunakan');
            redirect(BASE_URL . 'pegawai/create');
        }

        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $userData = [
                'username' => sanitize($_POST['username']),
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'role_id' => 3,
                'status' => 'aktif'
            ];
            $userId = $userModel->create($userData);

            $pegawaiModel = $this->model('PegawaiModel');
            $pegawaiModel->create([
                'user_id' => $userId,
                'nama' => sanitize($_POST['nama']),
                'nip' => sanitize($_POST['nip'])
            ]);

            $db->commit();
            set_flash('success', 'Pegawai berhasil ditambahkan');
            redirect(BASE_URL . 'pegawai');
        } catch (Exception $e) {
            $db->rollBack();
            set_flash('error', 'Gagal menambahkan pegawai: ' . $e->getMessage());
            redirect(BASE_URL . 'pegawai/create');
        }
    }

    public function edit($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pegawai')) {
            redirect(BASE_URL);
        }

        $pegawaiModel = $this->model('PegawaiModel');
        $pegawai = $pegawaiModel->find($id);

        if (!$pegawai) {
            set_flash('error', 'Pegawai tidak ditemukan');
            redirect(BASE_URL . 'pegawai');
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->find($pegawai['user_id']);

        $data['pegawai'] = $pegawai;
        $data['user'] = $user;

        $this->view('pegawai/edit', $data);
    }

    public function update($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pegawai')) {
            redirect(BASE_URL . 'pegawai');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'pegawai/edit/' . $id);
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'pegawai/edit/' . $id);
        }

        $rules = [
            'nama' => 'required',
            'nip' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'pegawai/edit/' . $id);
        }

        $pegawaiModel = $this->model('PegawaiModel');
        $pegawai = $pegawaiModel->find($id);

        if (!$pegawai) {
            set_flash('error', 'Pegawai tidak ditemukan');
            redirect(BASE_URL . 'pegawai');
        }

        $data = [
            'nama' => sanitize($_POST['nama']),
            'nip' => sanitize($_POST['nip'])
        ];

        $pegawaiModel->update($id, $data);

        if (!empty($_POST['password'])) {
            $userModel = $this->model('UserModel');
            $userModel->update($pegawai['user_id'], [
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
            ]);
        }

        if (!empty($_POST['status'])) {
            $userModel = $this->model('UserModel');
            $userModel->update($pegawai['user_id'], [
                'status' => sanitize($_POST['status'])
            ]);
        }

        set_flash('success', 'Pegawai berhasil diperbarui');
        redirect(BASE_URL . 'pegawai');
    }

    public function delete($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pegawai')) {
            redirect(BASE_URL . 'pegawai');
        }

        $pegawaiModel = $this->model('PegawaiModel');
        $pegawai = $pegawaiModel->find($id);

        if (!$pegawai) {
            set_flash('error', 'Pegawai tidak ditemukan');
            redirect(BASE_URL . 'pegawai');
        }

        $userModel = $this->model('UserModel');
        $userModel->delete($pegawai['user_id']);

        set_flash('success', 'Pegawai berhasil dihapus');
        redirect(BASE_URL . 'pegawai');
    }
}
