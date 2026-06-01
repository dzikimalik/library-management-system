<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $currentRole = isset($role) ? $role : Session::get('role'); ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Anggota</li>
            </ol>
        </nav>
        <h4>Data Anggota</h4>
    </div>
    <?php if ($currentRole !== 'Anggota'): ?>
        <a href="<?= BASE_URL ?>anggota/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Anggota
        </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>No Telp</th>
                        <?php if ($currentRole !== 'Anggota'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($anggota as $a): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= sanitize($a['nama']) ?></td>
                            <td><?= sanitize($a['nis']) ?></td>
                            <td><?= sanitize($a['nama_kelas']) ?></td>
                            <td><?= sanitize($a['no_telp']) ?></td>
                            <?php if ($currentRole !== 'Anggota'): ?>
                                <td>
                                    <a href="<?= BASE_URL ?>anggota/edit/<?= $a['id'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>anggota/delete/<?= $a['id'] ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
