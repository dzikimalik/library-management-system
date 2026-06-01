<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Data User</li>
            </ol>
        </nav>
        <h4>Data User</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($users as $u): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= sanitize($u['username']) ?></td>
                        <td><?= sanitize($u['nama_role']) ?></td>
                        <td>
                            <span class="badge bg-<?= $u['status'] === 'aktif' ? 'success' : 'danger' ?>">
                                <?= $u['status'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>user/edit/<?= $u['id'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>user/delete/<?= $u['id'] ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus">
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
