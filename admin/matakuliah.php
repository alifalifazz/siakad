<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

$data = $pdo->query("
    SELECT mk.*, d.nama AS nama_dosen FROM matakuliah mk
    LEFT JOIN dosen d ON mk.dosen_id = d.id
    ORDER BY mk.semester ASC, mk.nama_mk ASC
")->fetchAll();

$page_title = 'Data Mata Kuliah';
$active = '/sia/admin/matakuliah.php';
$menu_items = [
    ['label' => 'Dashboard', 'url' => '/sia/admin/index.php', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Data Mahasiswa', 'url' => '/sia/admin/mahasiswa.php', 'icon' => 'fas fa-user-graduate'],
    ['label' => 'Data Dosen', 'url' => '/sia/admin/dosen.php', 'icon' => 'fas fa-chalkboard-teacher'],
    ['label' => 'Mata Kuliah', 'url' => '/sia/admin/matakuliah.php', 'icon' => 'fas fa-book'],
];
require __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Mata Kuliah</h3>
        <a href="/sia/admin/matakuliah_form.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Mata Kuliah</a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead><tr><th>#</th><th>Kode</th><th>Nama MK</th><th>SKS</th><th>Semester</th><th>Dosen Pengampu</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($data as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($row['kode_mk']) ?></td>
                    <td><?= e($row['nama_mk']) ?></td>
                    <td><?= e($row['sks']) ?></td>
                    <td><?= e($row['semester']) ?></td>
                    <td><?= e($row['nama_dosen'] ?? '-') ?></td>
                    <td>
                        <a href="/sia/admin/matakuliah_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="/sia/admin/matakuliah_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger btn-delete-confirm"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($data)): ?>
                <tr><td colspan="7" class="text-center">Belum ada data mata kuliah.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
