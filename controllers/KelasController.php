<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class KelasController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kelas')) {
            redirect(BASE_URL);
        }

        $kelasModel = $this->model('KelasModel');
        $data['kelas'] = $kelasModel->all();

        $this->view('kelas/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kelas')) {
            redirect(BASE_URL);
        }

        $this->view('kelas/create');
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kelas')) {
            redirect(BASE_URL . 'kelas');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'kelas/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'kelas/create');
        }

        $rules = [
            'nama_kelas' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Nama kelas harus diisi');
            redirect(BASE_URL . 'kelas/create');
        }

        $data = [
            'nama_kelas' => sanitize($_POST['nama_kelas'])
        ];

        $kelasModel = $this->model('KelasModel');
        $kelasModel->create($data);

        set_flash('success', 'Kelas berhasil ditambahkan');
        redirect(BASE_URL . 'kelas');
    }

    public function edit($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kelas')) {
            redirect(BASE_URL);
        }

        $kelasModel = $this->model('KelasModel');
        $kelas = $kelasModel->find($id);

        if (!$kelas) {
            set_flash('error', 'Kelas tidak ditemukan');
            redirect(BASE_URL . 'kelas');
        }

        $data['kelas'] = $kelas;
        $this->view('kelas/edit', $data);
    }

    public function update($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kelas')) {
            redirect(BASE_URL . 'kelas');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'kelas/edit/' . $id);
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'kelas/edit/' . $id);
        }

        $rules = [
            'nama_kelas' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Nama kelas harus diisi');
            redirect(BASE_URL . 'kelas/edit/' . $id);
        }

        $kelasModel = $this->model('KelasModel');
        $kelas = $kelasModel->find($id);

        if (!$kelas) {
            set_flash('error', 'Kelas tidak ditemukan');
            redirect(BASE_URL . 'kelas');
        }

        $data = [
            'nama_kelas' => sanitize($_POST['nama_kelas'])
        ];

        $kelasModel->update($id, $data);

        set_flash('success', 'Kelas berhasil diperbarui');
        redirect(BASE_URL . 'kelas');
    }

    public function delete($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'kelas')) {
            redirect(BASE_URL . 'kelas');
        }

        $kelasModel = $this->model('KelasModel');
        $kelas = $kelasModel->find($id);

        if (!$kelas) {
            set_flash('error', 'Kelas tidak ditemukan');
            redirect(BASE_URL . 'kelas');
        }

        $kelasModel->delete($id);

        set_flash('success', 'Kelas berhasil dihapus');
        redirect(BASE_URL . 'kelas');
    }
}
