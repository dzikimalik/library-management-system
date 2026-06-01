<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Kelas</li>
            </ol>
        </nav>
        <h4>Data Kelas</h4>
    </div>
    <a href="<?= BASE_URL ?>kelas/create" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah Kelas
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kelas as $k): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= sanitize($k['nama_kelas']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>kelas/edit/<?= $k['id'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>kelas/delete/<?= $k['id'] ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
