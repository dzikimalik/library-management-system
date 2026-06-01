<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class AuthController extends Controller
{
    public function login()
    {
        $this->view('auth/login');
    }

    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'login');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'login');
        }

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Username dan password harus diisi');
            redirect(BASE_URL . 'login');
        }

        $username = sanitize($_POST['username']);
        $password = $_POST['password'];

        $userModel = $this->model('UserModel');
        $user = $userModel->getByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            set_flash('error', 'Username atau password salah');
            redirect(BASE_URL . 'login');
        }

        if ($user['status'] !== 'aktif') {
            set_flash('error', 'Akun Anda tidak aktif');
            redirect(BASE_URL . 'login');
        }

        $roleModel = $this->model('RoleModel');
        $role = $roleModel->find($user['role_id']);
        $roleName = $role['nama_role'];

        $nama = $username;
        if ($roleName === 'Admin') {
            $adminModel = $this->model('AdminModel');
            $admin = $adminModel->getByUserId($user['id']);
            $nama = $admin ? $admin['nama'] : $username;
        } elseif ($roleName === 'Kepala') {
            $kepalaModel = $this->model('KepalaModel');
            $kepala = $kepalaModel->getByUserId($user['id']);
            $nama = $kepala ? $kepala['nama'] : $username;
        } elseif ($roleName === 'Pegawai') {
            $pegawaiModel = $this->model('PegawaiModel');
            $pegawai = $pegawaiModel->getByUserId($user['id']);
            $nama = $pegawai ? $pegawai['nama'] : $username;
        } elseif ($roleName === 'Anggota') {
            $anggotaModel = $this->model('AnggotaModel');
            $anggota = $anggotaModel->getByUserId($user['id']);
            $nama = $anggota ? $anggota['nama'] : $username;
        }

        Session::set('user_id', $user['id']);
        Session::set('role', $roleName);
        Session::set('nama', $nama);
        Session::regenerate();

        set_flash('success', 'Selamat datang, ' . $nama);
        redirect(BASE_URL . 'dashboard');
    }

    public function logout()
    {
        Session::destroy();
        redirect(BASE_URL . 'login');
    }
}
