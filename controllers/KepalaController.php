<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class KepalaController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kepala')) {
            redirect(BASE_URL);
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT kepala.*, users.username, users.status
            FROM kepala
            JOIN users ON users.id = kepala.user_id
            ORDER BY kepala.id DESC
        ");
        $data['kepala'] = $stmt->fetchAll();

        $this->view('kepala/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kepala')) {
            redirect(BASE_URL);
        }

        $this->view('kepala/create');
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kepala')) {
            redirect(BASE_URL . 'kepala');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'kepala/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'kepala/create');
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
            redirect(BASE_URL . 'kepala/create');
        }

        $userModel = $this->model('UserModel');
        $existingUser = $userModel->getByUsername(sanitize($_POST['username']));
        if ($existingUser) {
            set_flash('error', 'Username sudah digunakan');
            redirect(BASE_URL . 'kepala/create');
        }

        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $userData = [
                'username' => sanitize($_POST['username']),
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'role_id' => 2,
                'status' => 'aktif'
            ];
            $userId = $userModel->create($userData);

            $kepalaModel = $this->model('KepalaModel');
            $kepalaModel->create([
                'user_id' => $userId,
                'nama' => sanitize($_POST['nama']),
                'nip' => sanitize($_POST['nip'])
            ]);

            $db->commit();
            set_flash('success', 'Kepala berhasil ditambahkan');
            redirect(BASE_URL . 'kepala');
        } catch (Exception $e) {
            $db->rollBack();
            set_flash('error', 'Gagal menambahkan kepala: ' . $e->getMessage());
            redirect(BASE_URL . 'kepala/create');
        }
    }

    public function edit($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kepala')) {
            redirect(BASE_URL);
        }

        $kepalaModel = $this->model('KepalaModel');
        $kepala = $kepalaModel->find($id);

        if (!$kepala) {
            set_flash('error', 'Kepala tidak ditemukan');
            redirect(BASE_URL . 'kepala');
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->find($kepala['user_id']);

        $data['kepala'] = $kepala;
        $data['user'] = $user;

        $this->view('kepala/edit', $data);
    }

    public function update($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kepala')) {
            redirect(BASE_URL . 'kepala');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'kepala/edit/' . $id);
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'kepala/edit/' . $id);
        }

        $rules = [
            'nama' => 'required',
            'nip' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'kepala/edit/' . $id);
        }

        $kepalaModel = $this->model('KepalaModel');
        $kepala = $kepalaModel->find($id);

        if (!$kepala) {
            set_flash('error', 'Kepala tidak ditemukan');
            redirect(BASE_URL . 'kepala');
        }

        $data = [
            'nama' => sanitize($_POST['nama']),
            'nip' => sanitize($_POST['nip'])
        ];

        $kepalaModel->update($id, $data);

        if (!empty($_POST['password'])) {
            $userModel = $this->model('UserModel');
            $userModel->update($kepala['user_id'], [
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
            ]);
        }

        if (!empty($_POST['status'])) {
            $userModel = $this->model('UserModel');
            $userModel->update($kepala['user_id'], [
                'status' => sanitize($_POST['status'])
            ]);
        }

        set_flash('success', 'Kepala berhasil diperbarui');
        redirect(BASE_URL . 'kepala');
    }

    public function delete($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kepala')) {
            redirect(BASE_URL . 'kepala');
        }

        $kepalaModel = $this->model('KepalaModel');
        $kepala = $kepalaModel->find($id);

        if (!$kepala) {
            set_flash('error', 'Kepala tidak ditemukan');
            redirect(BASE_URL . 'kepala');
        }

        $userModel = $this->model('UserModel');
        $userModel->delete($kepala['user_id']);

        set_flash('success', 'Kepala berhasil dihapus');
        redirect(BASE_URL . 'kepala');
    }
}
