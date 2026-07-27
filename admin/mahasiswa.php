<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

$data = $pdo->query("
    SELECT m.*, u.username FROM mahasiswa m
    JOIN users u ON m.user_id = u.id
    ORDER BY m.nama ASC
")->fetchAll();

$page_title = 'Data Mahasiswa';
$active = '/sia/admin/mahasiswa.php';
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
        <h3 class="card-title">Daftar Mahasiswa</h3>
        <a href="/sia/admin/mahasiswa_form.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Mahasiswa</a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
            <tr>
                <th>#</th><th>NIM</th><th>Nama</th><th>Prodi</th><th>Angkatan</th><th>Username</th><th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($row['nim']) ?></td>
                    <td><?= e($row['nama']) ?></td>
                    <td><?= e($row['prodi']) ?></td>
                    <td><?= e($row['angkatan']) ?></td>
                    <td><?= e($row['username']) ?></td>
                    <td>
                        <a href="/sia/admin/mahasiswa_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="/sia/admin/mahasiswa_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger btn-delete-confirm"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($data)): ?>
                <tr><td colspan="7" class="text-center">Belum ada data mahasiswa.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
