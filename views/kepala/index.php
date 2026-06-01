<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Kepala</li>
            </ol>
        </nav>
        <h4>Data Kepala</h4>
    </div>
    <a href="<?= BASE_URL ?>kepala/create" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah Kepala
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kepala as $k): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= sanitize($k['nama']) ?></td>
                        <td><?= sanitize($k['nip']) ?></td>
                        <td><?= sanitize($k['username']) ?></td>
                        <td>
                            <span class="badge bg-<?= $k['status'] === 'aktif' ? 'success' : 'danger' ?>">
                                <?= $k['status'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>kepala/edit/<?= $k['id'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>kepala/delete/<?= $k['id'] ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus">
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
