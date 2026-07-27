<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('dosen');

$dosen_id = $_SESSION['dosen_id'];
$mk_list = $pdo->prepare("SELECT * FROM matakuliah WHERE dosen_id = ? ORDER BY semester");
$mk_list->execute([$dosen_id]);
$mk_list = $mk_list->fetchAll();

$page_title = 'Dashboard Dosen';
$active = '/sia/dosen/index.php';
$menu_items = [
    ['label' => 'Dashboard', 'url' => '/sia/dosen/index.php', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Input Nilai', 'url' => '/sia/dosen/input_nilai.php', 'icon' => 'fas fa-pen'],
];
require __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header"><h3 class="card-title">Mata Kuliah yang Diampu</h3></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead><tr><th>#</th><th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Semester</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($mk_list as $i => $mk): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($mk['kode_mk']) ?></td>
                    <td><?= e($mk['nama_mk']) ?></td>
                    <td><?= e($mk['sks']) ?></td>
                    <td><?= e($mk['semester']) ?></td>
                    <td><a href="/sia/dosen/input_nilai.php?mk_id=<?= $mk['id'] ?>" class="btn btn-sm btn-primary">Input Nilai</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($mk_list)): ?>
                <tr><td colspan="6" class="text-center">Belum ada mata kuliah yang diampu.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
