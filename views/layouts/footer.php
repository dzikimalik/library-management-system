</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('.datatable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        pageLength: 25,
        responsive: true
    });

    $('.btn-delete').on('click', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = url;
        });
    });

    $('#sidebarToggle').on('click', function () {
        $('#sidebar').toggleClass('show');
        $('#sidebarOverlay').toggleClass('show');
    });

    $('#sidebarOverlay').on('click', function () {
        $('#sidebar').removeClass('show');
        $('#sidebarOverlay').removeClass('show');
    });
});

<?php $success = get_flash('success'); $error = get_flash('error'); ?>
<?php if ($success): ?>
Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= addslashes($success) ?>', timer: 3000, showConfirmButton: false });
<?php elseif ($error): ?>
Swal.fire({ icon: 'error', title: 'Gagal!', text: '<?= addslashes($error) ?>', timer: 5000, showConfirmButton: false });
<?php endif; ?>
</script>
</body>
</html>
