<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('dosen');

$dosen_id = $_SESSION['dosen_id'];
$mk_id = $_GET['mk_id'] ?? null;

// Pastikan mata kuliah ini benar milik dosen yang login
$stmt = $pdo->prepare("SELECT * FROM matakuliah WHERE id = ? AND dosen_id = ?");
$stmt->execute([$mk_id, $dosen_id]);
$mk = $stmt->fetch();
if (!$mk) {
    die('Mata kuliah tidak ditemukan atau bukan milik Anda.');
}

// Ambil semua mahasiswa yang mengambil KRS mata kuliah ini
$stmt = $pdo->prepare("
    SELECT krs.id AS krs_id, mhs.nim, mhs.nama, n.nilai_angka, n.nilai_huruf
    FROM krs
    JOIN mahasiswa mhs ON krs.mahasiswa_id = mhs.id
    LEFT JOIN nilai n ON n.krs_id = krs.id
    WHERE krs.matakuliah_id = ?
    ORDER BY mhs.nama ASC
");
$stmt->execute([$mk_id]);
$peserta = $stmt->fetchAll();

$page_title = 'Input Nilai - ' . $mk['nama_mk'];
$active = '/sia/dosen/input_nilai.php';
$menu_items = [
    ['label' => 'Dashboard', 'url' => '/sia/dosen/index.php', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Input Nilai', 'url' => '/sia/dosen/input_nilai.php', 'icon' => 'fas fa-pen'],
];
require __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= e($mk['nama_mk']) ?> (<?= e($mk['kode_mk']) ?>)</h3>
    </div>
    <form action="/sia/dosen/simpan_nilai.php" method="POST">
        <input type="hidden" name="mk_id" value="<?= e($mk_id) ?>">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead><tr><th>#</th><th>NIM</th><th>Nama</th><th>Nilai Angka (0-100)</th><th>Huruf Saat Ini</th></tr></thead>
                <tbody>
                <?php foreach ($peserta as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($p['nim']) ?></td>
                        <td><?= e($p['nama']) ?></td>
                        <td>
                            <input type="number" step="0.01" min="0" max="100"
                                   name="nilai[<?= $p['krs_id'] ?>]"
                                   class="form-control form-control-sm"
                                   value="<?= e($p['nilai_angka']) ?>">
                        </td>
                        <td><?= e($p['nilai_huruf'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($peserta)): ?>
                    <tr><td colspan="5" class="text-center">Belum ada mahasiswa yang mengambil mata kuliah ini.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($peserta)): ?>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Nilai</button>
        </div>
        <?php endif; ?>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
