<?php
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';

class LaporanController extends Controller
{
    public function index()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'laporan')) {
            redirect(BASE_URL);
        }

        $this->view('laporan/index');
    }

    public function peminjaman()
    {
        $this->checkLaporanAccess();

        $startDate = isset($_GET['start_date']) ? sanitize($_GET['start_date']) : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? sanitize($_GET['end_date']) : date('Y-m-d');

        $peminjamanModel = $this->model('PeminjamanModel');
        $all = $peminjamanModel->getAllWithRelation();
        $data = array_filter($all, function ($p) use ($startDate, $endDate) {
            return $p['tgl_pinjam'] >= $startDate && $p['tgl_pinjam'] <= $endDate;
        });

        $this->renderLaporanHtml(
            'LAPORAN PEMINJAMAN',
            $startDate,
            $endDate,
            ['No', 'Anggota', 'Buku', 'Petugas', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status'],
            $data,
            function ($row, $index) {
                $statusLabel = $row['status'];
                return [
                    $index + 1,
                    $row['nama_anggota'],
                    $row['judul_buku'],
                    $row['nama_petugas'],
                    formatDate($row['tgl_pinjam']),
                    formatDate($row['tgl_jatuh_tempo']),
                    $row['tgl_kembali'] ? formatDate($row['tgl_kembali']) : '-',
                    ucfirst($statusLabel)
                ];
            }
        );
    }

    public function pengembalian()
    {
        $this->checkLaporanAccess();

        $startDate = isset($_GET['start_date']) ? sanitize($_GET['start_date']) : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? sanitize($_GET['end_date']) : date('Y-m-d');

        $pengembalianModel = $this->model('PengembalianModel');
        $all = $pengembalianModel->getAllWithRelation();
        $data = array_filter($all, function ($p) use ($startDate, $endDate) {
            return $p['tgl_kembali'] >= $startDate && $p['tgl_kembali'] <= $endDate;
        });

        $this->renderLaporanHtml(
            'LAPORAN PENGEMBALIAN',
            $startDate,
            $endDate,
            ['No', 'Anggota', 'Buku', 'Petugas', 'Tgl Pinjam', 'Tgl Kembali', 'Denda'],
            $data,
            function ($row, $index) {
                return [
                    $index + 1,
                    $row['nama_anggota'],
                    $row['judul_buku'],
                    $row['nama_petugas'],
                    formatDate($row['tgl_pinjam']),
                    formatDate($row['tgl_kembali']),
                    rupiah($row['denda'])
                ];
            }
        );
    }

    public function kunjungan()
    {
        $this->checkLaporanAccess();

        $startDate = isset($_GET['start_date']) ? sanitize($_GET['start_date']) : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? sanitize($_GET['end_date']) : date('Y-m-d');

        $kunjunganModel = $this->model('KunjunganModel');
        $all = $kunjunganModel->getAllWithRelation();
        $data = array_filter($all, function ($k) use ($startDate, $endDate) {
            return $k['tgl_kunjungan'] >= $startDate && $k['tgl_kunjungan'] <= $endDate;
        });

        $this->renderLaporanHtml(
            'LAPORAN KUNJUNGAN',
            $startDate,
            $endDate,
            ['No', 'Nama Pengunjung', 'Tanggal', 'Keperluan'],
            $data,
            function ($row, $index) {
                return [
                    $index + 1,
                    $row['nama_pengunjung'],
                    formatDate($row['tgl_kunjungan']),
                    $row['keperluan']
                ];
            }
        );
    }

    public function denda()
    {
        $this->checkLaporanAccess();

        $startDate = isset($_GET['start_date']) ? sanitize($_GET['start_date']) : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? sanitize($_GET['end_date']) : date('Y-m-d');

        $dendaModel = $this->model('DendaModel');
        $all = $dendaModel->getAllWithRelation();
        $data = array_filter($all, function ($d) use ($startDate, $endDate) {
            return $d['tgl_kembali'] >= $startDate && $d['tgl_kembali'] <= $endDate;
        });

        $this->renderLaporanHtml(
            'LAPORAN DENDA',
            $startDate,
            $endDate,
            ['No', 'Anggota', 'Buku', 'Jumlah Denda', 'Status', 'Tgl Bayar'],
            $data,
            function ($row, $index) {
                return [
                    $index + 1,
                    $row['nama_anggota'],
                    $row['judul_buku'],
                    rupiah($row['jumlah_denda']),
                    ucfirst($row['status_bayar']),
                    $row['tgl_bayar'] ? formatDate($row['tgl_bayar']) : '-'
                ];
            }
        );
    }

    private function checkLaporanAccess()
    {
        if (!Middleware::isLoggedIn()) {
            redirect(BASE_URL . 'login');
        }

        $role = Session::get('role');
        if (!Middleware::checkAccess($role, 'laporan')) {
            redirect(BASE_URL);
        }
    }

    private function renderLaporanHtml($title, $startDate, $endDate, $headers, $data, $rowCallback)
    {
        header('Content-Type: text/html; charset=utf-8');
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title><?= $title ?></title>
            <style>
                @media print {
                    @page { size: landscape; margin: 1.5cm; }
                    body { font-family: 'Times New Roman', Times, serif; font-size: 12px; }
                    .btn-print { display: none; }
                }
                body { font-family: 'Times New Roman', Times, serif; font-size: 12px; padding: 20px; }
                .kop {
                    text-align: center;
                    border-bottom: 3px double #000;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                }
                .kop h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
                .kop h3 { margin: 5px 0; font-size: 14px; }
                .kop p { margin: 2px 0; font-size: 11px; }
                .title { text-align: center; font-size: 16px; font-weight: bold; margin: 20px 0; text-decoration: underline; }
                .periode { text-align: center; font-size: 12px; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                table th { background-color: #f0f0f0; font-weight: bold; }
                table th, table td { border: 1px solid #000; padding: 6px 8px; text-align: left; font-size: 11px; }
                table th { text-align: center; }
                .footer { margin-top: 50px; }
                .footer .ttd { float: right; width: 250px; text-align: center; }
                .footer .ttd p { margin: 5px 0; }
                .btn-print { display: block; text-align: center; margin: 20px 0; }
                .btn-print a { padding: 10px 20px; background: #4a90d9; color: white; text-decoration: none; border-radius: 4px; }
                .clear { clear: both; }
            </style>
        </head>
        <body>
            <div class="kop">
                <h1>PERPUSTAKAAN SMP NEGERI 3 ATADEI</h1>
                <h3><?= APP_NAME ?></h3>
                <p>Jalan ... (isi alamat SMP 3 Atadei)</p>
                <p>Telp: (...) ....... | Email: smp3atadei@sch.id</p>
            </div>

            <div class="title"><?= $title ?></div>
            <div class="periode">Periode: <?= formatDate($startDate) ?> s.d. <?= formatDate($endDate) ?></div>

            <table>
                <thead>
                    <tr>
                        <?php foreach ($headers as $h): ?>
                            <th><?= $h ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="<?= count($headers) ?>" style="text-align:center">Tidak ada data</td></tr>
                    <?php else: ?>
                        <?php $i = 0; ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <?php foreach ($rowCallback($row, $i) as $cell): ?>
                                    <td><?= $cell ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="footer">
                <div class="ttd">
                    <p>Kota, <?= tgl_indo(date('Y-m-d')) ?></p>
                    <p>Kepala Perpustakaan</p>
                    <br><br><br>
                    <p>_________________________</p>
                </div>
                <div class="clear"></div>
            </div>

            <div class="btn-print">
                <a href="#" onclick="window.print(); return false;">Cetak Laporan</a>
                <a href="<?= BASE_URL ?>laporan" style="margin-left:10px;background:#6c757d;">Kembali</a>
            </div>

            <script>window.print();</script>
        </body>
        </html>
        <?php
        exit;
    }
}
