<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

$id = $_GET['id'] ?? null;
$row = ['kode_mk' => '', 'nama_mk' => '', 'sks' => 3, 'semester' => 1, 'dosen_id' => null];
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM matakuliah WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: $row;
}
$dosen_list = $pdo->query("SELECT id, nama FROM dosen ORDER BY nama")->fetchAll();

$page_title = $id ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah';
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
    <form action="/sia/admin/matakuliah_save.php" method="POST">
        <div class="card-body">
            <?php if ($id): ?><input type="hidden" name="id" value="<?= e($id) ?>"><?php endif; ?>
            <div class="form-group mb-3">
                <label>Kode Mata Kuliah</label>
                <input type="text" name="kode_mk" class="form-control" value="<?= e($row['kode_mk']) ?>" required>
            </div>
            <div class="form-group mb-3">
                <label>Nama Mata Kuliah</label>
                <input type="text" name="nama_mk" class="form-control" value="<?= e($row['nama_mk']) ?>" required>
            </div>
            <div class="form-group mb-3">
                <label>SKS</label>
                <input type="number" name="sks" min="1" max="6" class="form-control" value="<?= e($row['sks']) ?>" required>
            </div>
            <div class="form-group mb-3">
                <label>Semester</label>
                <input type="number" name="semester" min="1" max="14" class="form-control" value="<?= e($row['semester']) ?>" required>
            </div>
            <div class="form-group mb-3">
                <label>Dosen Pengampu</label>
                <select name="dosen_id" class="form-control">
                    <option value="">-- Pilih Dosen --</option>
                    <?php foreach ($dosen_list as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= $row['dosen_id'] == $d['id'] ? 'selected' : '' ?>><?= e($d['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/sia/admin/matakuliah.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
