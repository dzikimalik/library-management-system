<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class UserController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'user')) {
            redirect(BASE_URL);
        }

        $userModel = $this->model('UserModel');
        $data['users'] = $userModel->getAllWithRole();

        $this->view('user/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'user')) {
            redirect(BASE_URL);
        }

        $roleModel = $this->model('RoleModel');
        $data['roles'] = $roleModel->all();

        $this->view('user/create', $data);
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'user')) {
            redirect(BASE_URL . 'user');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'user/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'user/create');
        }

        $rules = [
            'username' => 'required',
            'password' => 'required',
            'role_id' => 'required|numeric'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'user/create');
        }

        $userModel = $this->model('UserModel');
        $existingUser = $userModel->getByUsername(sanitize($_POST['username']));
        if ($existingUser) {
            set_flash('error', 'Username sudah digunakan');
            redirect(BASE_URL . 'user/create');
        }

        $data = [
            'username' => sanitize($_POST['username']),
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'role_id' => (int)$_POST['role_id'],
            'status' => isset($_POST['status']) ? sanitize($_POST['status']) : 'aktif'
        ];

        $userModel->create($data);

        set_flash('success', 'User berhasil ditambahkan');
        redirect(BASE_URL . 'user');
    }

    public function edit($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'user')) {
            redirect(BASE_URL);
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->getWithRole($id);

        if (!$user) {
            set_flash('error', 'User tidak ditemukan');
            redirect(BASE_URL . 'user');
        }

        $roleModel = $this->model('RoleModel');

        $data['user'] = $user;
        $data['roles'] = $roleModel->all();

        $this->view('user/edit', $data);
    }

    public function update($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'user')) {
            redirect(BASE_URL . 'user');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'user/edit/' . $id);
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'user/edit/' . $id);
        }

        $rules = [
            'username' => 'required',
            'role_id' => 'required|numeric'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'user/edit/' . $id);
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->find($id);

        if (!$user) {
            set_flash('error', 'User tidak ditemukan');
            redirect(BASE_URL . 'user');
        }

        $data = [
            'username' => sanitize($_POST['username']),
            'role_id' => (int)$_POST['role_id'],
            'status' => isset($_POST['status']) ? sanitize($_POST['status']) : 'aktif'
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        $userModel->update($id, $data);

        set_flash('success', 'User berhasil diperbarui');
        redirect(BASE_URL . 'user');
    }

    public function delete($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'user')) {
            redirect(BASE_URL . 'user');
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->find($id);

        if (!$user) {
            set_flash('error', 'User tidak ditemukan');
            redirect(BASE_URL . 'user');
        }

        $userModel->delete($id);

        set_flash('success', 'User berhasil dihapus');
        redirect(BASE_URL . 'user');
    }
}
