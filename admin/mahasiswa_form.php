<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

$id = $_GET['id'] ?? null;
$row = ['nim' => '', 'nama' => '', 'prodi' => '', 'angkatan' => date('Y'), 'username' => ''];
if ($id) {
    $stmt = $pdo->prepare("SELECT m.*, u.username FROM mahasiswa m JOIN users u ON m.user_id = u.id WHERE m.id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: $row;
}

$page_title = $id ? 'Edit Mahasiswa' : 'Tambah Mahasiswa';
$menu_items = [
    ['label' => 'Dashboard', 'url' => '/sia/admin/index.php', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Data Mahasiswa', 'url' => '/sia/admin/mahasiswa.php', 'icon' => 'fas fa-user-graduate'],
    ['label' => 'Data Dosen', 'url' => '/sia/admin/dosen.php', 'icon' => 'fas fa-chalkboard-teacher'],
    ['label' => 'Mata Kuliah', 'url' => '/sia/admin/matakuliah.php', 'icon' => 'fas fa-book'],
];
require __DIR__ . '/../includes/header.php';
?>

<div class="card card-primary">
    <div class="card-header"><h3 class="card-title"><?= e($page_title) ?></h3></div>
    <form action="/sia/admin/mahasiswa_save.php" method="POST">
        <div class="card-body">
            <?php if ($id): ?><input type="hidden" name="id" value="<?= e($id) ?>"><?php endif; ?>
            <div class="form-group mb-3">
                <label>NIM</label>
                <input type="text" name="nim" class="form-control" value="<?= e($row['nim']) ?>" required>
            </div>
            <div class="form-group mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= e($row['nama']) ?>" required>
            </div>
            <div class="form-group mb-3">
                <label>Program Studi</label>
                <input type="text" name="prodi" class="form-control" value="<?= e($row['prodi']) ?>" required>
            </div>
            <div class="form-group mb-3">
                <label>Angkatan</label>
                <input type="number" name="angkatan" class="form-control" value="<?= e($row['angkatan']) ?>" required>
            </div>
            <div class="form-group mb-3">
                <label>Username Login</label>
                <input type="text" name="username" class="form-control" value="<?= e($row['username']) ?>" required <?= $id ? 'readonly' : '' ?>>
            </div>
            <?php if (!$id): ?>
            <div class="form-group mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password default jika kosong: password">
            </div>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/sia/admin/mahasiswa.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
