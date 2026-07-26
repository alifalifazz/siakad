// Custom JS untuk SI Akademik
document.addEventListener('DOMContentLoaded', function () {
    // Konfirmasi sebelum hapus data
    document.querySelectorAll('.btn-delete-confirm').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
});
