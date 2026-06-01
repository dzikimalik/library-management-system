$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Auto submit date filter on change
    $('.date-filter').on('change', function() {
        $(this).closest('form').submit();
    });

    // Preview image before upload
    $('.preview-image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
});
