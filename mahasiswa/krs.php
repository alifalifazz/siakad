<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('mahasiswa');

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$tahun_ajaran = $_GET['ta'] ?? '2025/2026';

// Mata kuliah yang sudah diambil pada tahun ajaran ini
$stmt = $pdo->prepare("
    SELECT krs.id AS krs_id, mk.id AS mk_id, mk.kode_mk, mk.nama_mk, mk.sks, mk.semester
    FROM krs
    JOIN matakuliah mk ON krs.matakuliah_id = mk.id
    WHERE krs.mahasiswa_id = ? AND krs.tahun_ajaran = ?
    ORDER BY mk.semester
");
$stmt->execute([$mahasiswa_id, $tahun_ajaran]);
$sudah_diambil = $stmt->fetchAll();
$id_sudah_diambil = array_column($sudah_diambil, 'mk_id');

// Total SKS yang sudah diambil pada tahun ajaran ini
$total_sks_diambil = array_sum(array_column($sudah_diambil, 'sks'));

// Mata kuliah yang tersedia (belum diambil pada tahun ajaran ini)
$mk_tersedia = $pdo->query("SELECT * FROM matakuliah ORDER BY semester, nama_mk")->fetchAll();

$page_title = 'Isi KRS';
$active = '/sia/mahasiswa/krs.php';
$menu_items = [
    ['label' => 'Dashboard', 'url' => '/sia/mahasiswa/index.php', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Isi KRS', 'url' => '/sia/mahasiswa/krs.php', 'icon' => 'fas fa-list-alt'],
    ['label' => 'KHS & IPK', 'url' => '/sia/mahasiswa/khs.php', 'icon' => 'fas fa-chart-line'],
];
require __DIR__ . '/../includes/header.php';
?>

<div class="card mb-3">
    <div class="card-body">
        <form class="form-inline" method="GET">
            <label class="mr-2 me-2">Tahun Ajaran:</label>
            <input type="text" name="ta" value="<?= e($tahun_ajaran) ?>" class="form-control form-control-sm me-2" style="width:150px" placeholder="2025/2026">
            <button class="btn btn-sm btn-secondary">Tampilkan</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pilih Mata Kuliah - Tahun Ajaran <?= e($tahun_ajaran) ?></h3>
    </div>
    <form action="/sia/mahasiswa/simpan_krs.php" method="POST">
        <input type="hidden" name="tahun_ajaran" value="<?= e($tahun_ajaran) ?>">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead><tr><th></th><th>Kode</th><th>Nama MK</th><th>SKS</th><th>Semester</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($mk_tersedia as $mk): ?>
                    <?php $sudah = in_array($mk['id'], $id_sudah_diambil); ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="mk_id[]" value="<?= $mk['id'] ?>" <?= $sudah ? 'checked disabled' : '' ?>>
                        </td>
                        <td><?= e($mk['kode_mk']) ?></td>
                        <td><?= e($mk['nama_mk']) ?></td>
                        <td><?= e($mk['sks']) ?></td>
                        <td><?= e($mk['semester']) ?></td>
                        <td><?= $sudah ? '<span class="badge bg-success">Sudah diambil</span>' : '<span class="badge bg-secondary">Belum diambil</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <span>Total SKS sudah diambil tahun ajaran ini: <strong><?= $total_sks_diambil ?></strong> SKS (maks. 24 SKS)</span>
            <button type="submit" class="btn btn-primary">Simpan KRS</button>
        </div>
    </form>
</div>

<?php if (!empty($sudah_diambil)): ?>
<div class="card mt-3">
    <div class="card-header"><h3 class="card-title">Mata Kuliah Terdaftar</h3></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead><tr><th>#</th><th>Kode</th><th>Nama MK</th><th>SKS</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($sudah_diambil as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($row['kode_mk']) ?></td>
                    <td><?= e($row['nama_mk']) ?></td>
                    <td><?= e($row['sks']) ?></td>
                    <td><a href="/sia/mahasiswa/hapus_krs.php?krs_id=<?= $row['krs_id'] ?>" class="btn btn-sm btn-danger btn-delete-confirm"><i class="fas fa-trash"></i> Batalkan</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
