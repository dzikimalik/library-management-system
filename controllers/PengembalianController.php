<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class PengembalianController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pengembalian')) {
            redirect(BASE_URL);
        }

        $pengembalianModel = $this->model('PengembalianModel');
        $data['pengembalian'] = $pengembalianModel->getAllWithRelation();

        $this->view('pengembalian/index', $data);
    }

    public function create()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pengembalian')) {
            redirect(BASE_URL);
        }

        if ($role === 'Anggota') {
            set_flash('error', 'Akses ditolak');
            redirect(BASE_URL . 'pengembalian');
        }

        $peminjamanModel = $this->model('PeminjamanModel');
        $data['peminjaman'] = $peminjamanModel->getActive();

        $this->view('pengembalian/create', $data);
    }

    public function store()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'pengembalian') || $role === 'Anggota') {
            redirect(BASE_URL . 'pengembalian');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . 'pengembalian/create');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            set_flash('error', 'Token CSRF tidak valid');
            redirect(BASE_URL . 'pengembalian/create');
        }

        $rules = [
            'peminjaman_id' => 'required|numeric'
        ];
        $errors = $this->validate($rules, $_POST);
        if (!empty($errors)) {
            set_flash('error', 'Pilih peminjaman yang akan dikembalikan');
            redirect(BASE_URL . 'pengembalian/create');
        }

        $peminjamanId = (int)$_POST['peminjaman_id'];

        $peminjamanModel = $this->model('PeminjamanModel');
        $peminjaman = $peminjamanModel->find($peminjamanId);

        if (!$peminjaman) {
            set_flash('error', 'Peminjaman tidak ditemukan');
            redirect(BASE_URL . 'pengembalian/create');
        }

        if ($peminjaman['status'] !== 'dipinjam' && $peminjaman['status'] !== 'terlambat') {
            set_flash('error', 'Peminjaman sudah dikembalikan');
            redirect(BASE_URL . 'pengembalian/create');
        }

        $today = new DateTime();
        $jatuhTempo = new DateTime($peminjaman['tgl_jatuh_tempo']);
        $daysLate = $today > $jatuhTempo ? (int)$jatuhTempo->diff($today)->days : 0;
        $dendaAmount = $daysLate * 1000;

        $newStatus = 'dikembalikan';

        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $peminjamanModel->update($peminjamanId, [
                'tgl_kembali' => date('Y-m-d'),
                'status' => $newStatus
            ]);

            $pengembalianData = [
                'peminjaman_id' => $peminjamanId,
                'user_id' => Session::get('user_id'),
                'tgl_kembali' => date('Y-m-d'),
                'denda' => $dendaAmount
            ];

            $pengembalianModel = $this->model('PengembalianModel');
            $pengembalianId = $pengembalianModel->create($pengembalianData);

            if ($dendaAmount > 0) {
                $dendaModel = $this->model('DendaModel');
                $dendaModel->create([
                    'pengembalian_id' => $pengembalianId,
                    'jumlah_denda' => $dendaAmount,
                    'status_bayar' => 'belum',
                    'tgl_bayar' => null
                ]);
            }

            $bukuModel = $this->model('BukuModel');
            $bukuModel->updateStok($peminjaman['buku_id'], 1);

            $db->commit();

            $msg = 'Pengembalian berhasil diproses';
            if ($dendaAmount > 0) {
                $msg .= '. Denda: ' . rupiah($dendaAmount);
            }
            set_flash('success', $msg);
            redirect(BASE_URL . 'pengembalian');
        } catch (Exception $e) {
            $db->rollBack();
            set_flash('error', 'Gagal memproses pengembalian');
            redirect(BASE_URL . 'pengembalian/create');
        }
    }
}
