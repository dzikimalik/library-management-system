<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $currentRole = isset($role) ? $role : Session::get('role'); ?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Buku</li>
            </ol>
        </nav>
        <h4>Data Buku</h4>
    </div>
    <?php if ($currentRole !== 'Anggota'): ?>
        <a href="<?= BASE_URL ?>buku/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Buku
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
                        <th>Cover</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>ISBN</th>
                        <th>Tahun</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Rak</th>
                        <?php if ($currentRole !== 'Anggota'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($buku as $b): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if (!empty($b['cover'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= $b['cover'] ?>" alt="Cover" width="50" height="70" style="object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <div style="width:50px;height:70px;background:#e9ecef;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#adb5bd;">
                                        <i class="fas fa-book"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= sanitize($b['judul']) ?></td>
                            <td><?= sanitize($b['pengarang']) ?></td>
                            <td><?= sanitize($b['penerbit']) ?></td>
                            <td><?= sanitize($b['isbn']) ?></td>
                            <td><?= (int)$b['tahun'] ?></td>
                            <td><?= sanitize($b['kategori']) ?></td>
                            <td><?= (int)$b['stok'] ?></td>
                            <td><?= sanitize($b['rak']) ?></td>
                            <?php if ($currentRole !== 'Anggota'): ?>
                                <td>
                                    <a href="<?= BASE_URL ?>buku/edit/<?= $b['id'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>buku/delete/<?= $b['id'] ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus">
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
