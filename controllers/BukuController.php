<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class BukuController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'buku')) {
            redirect(BASE_URL);
        }

        $bukuModel = $this->model('BukuModel');
        $data['buku'] = $bukuModel->all();
        $data['role'] = $role;

        $this->view('buku/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'buku')) {
            redirect(BASE_URL);
        }

        if ($role === 'Anggota') {
            set_flash('error', 'Akses ditolak');
            redirect(BASE_URL . 'buku');
        }

        $this->view('buku/create');
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'buku') || $role === 'Anggota') {
            redirect(BASE_URL . 'buku');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'buku/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'buku/create');
        }

        $rules = [
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'isbn' => 'required',
            'tahun' => 'required|numeric',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'rak' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'buku/create');
        }

        $data = [
            'judul' => sanitize($_POST['judul']),
            'pengarang' => sanitize($_POST['pengarang']),
            'penerbit' => sanitize($_POST['penerbit']),
            'isbn' => sanitize($_POST['isbn']),
            'tahun' => (int)$_POST['tahun'],
            'kategori' => sanitize($_POST['kategori']),
            'stok' => (int)$_POST['stok'],
            'rak' => sanitize($_POST['rak'])
        ];

        $cover = $this->handleCoverUpload();
        if ($cover) {
            $data['cover'] = $cover;
        }

        $bukuModel = $this->model('BukuModel');
        $bukuModel->create($data);

        set_flash('success', 'Buku berhasil ditambahkan');
        redirect(BASE_URL . 'buku');
    }

    public function edit($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'buku')) {
            redirect(BASE_URL);
        }

        if ($role === 'Anggota') {
            set_flash('error', 'Akses ditolak');
            redirect(BASE_URL . 'buku');
        }

        $bukuModel = $this->model('BukuModel');
        $buku = $bukuModel->find($id);

        if (!$buku) {
            set_flash('error', 'Buku tidak ditemukan');
            redirect(BASE_URL . 'buku');
        }

        $data['buku'] = $buku;
        $this->view('buku/edit', $data);
    }

    public function update($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'buku') || $role === 'Anggota') {
            redirect(BASE_URL . 'buku');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'buku/edit/' . $id);
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'buku/edit/' . $id);
        }

        $rules = [
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'isbn' => 'required',
            'tahun' => 'required|numeric',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'rak' => 'required'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Semua field harus diisi dengan benar');
            redirect(BASE_URL . 'buku/edit/' . $id);
        }

        $bukuModel = $this->model('BukuModel');
        $buku = $bukuModel->find($id);

        if (!$buku) {
            set_flash('error', 'Buku tidak ditemukan');
            redirect(BASE_URL . 'buku');
        }

        $data = [
            'judul' => sanitize($_POST['judul']),
            'pengarang' => sanitize($_POST['pengarang']),
            'penerbit' => sanitize($_POST['penerbit']),
            'isbn' => sanitize($_POST['isbn']),
            'tahun' => (int)$_POST['tahun'],
            'kategori' => sanitize($_POST['kategori']),
            'stok' => (int)$_POST['stok'],
            'rak' => sanitize($_POST['rak'])
        ];

        $cover = $this->handleCoverUpload();
        if ($cover) {
            if (!empty($buku['cover'])) {
                $oldFile = __DIR__ . '/../uploads/' . $buku['cover'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            $data['cover'] = $cover;
        }

        $bukuModel->update($id, $data);

        set_flash('success', 'Buku berhasil diperbarui');
        redirect(BASE_URL . 'buku');
    }

    public function delete($id)
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'buku') || $role === 'Anggota') {
            redirect(BASE_URL . 'buku');
        }

        $bukuModel = $this->model('BukuModel');
        $buku = $bukuModel->find($id);

        if (!$buku) {
            set_flash('error', 'Buku tidak ditemukan');
            redirect(BASE_URL . 'buku');
        }

        if (!empty($buku['cover'])) {
            $oldFile = __DIR__ . '/../uploads/' . $buku['cover'];
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $bukuModel->delete($id);

        set_flash('success', 'Buku berhasil dihapus');
        redirect(BASE_URL . 'buku');
    }

    private function handleCoverUpload()
    {
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                set_flash('error', 'Format file harus JPG/JPEG/PNG/GIF');
                return null;
            }
            $targetDir = __DIR__ . '/../uploads/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $filename = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetDir . $filename)) {
                return $filename;
            }
        }
        return null;
    }
}
