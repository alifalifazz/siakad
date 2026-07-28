<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('mahasiswa');

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$hasil = hitung_ipk($pdo, $mahasiswa_id);

// Kelompokkan detail per tahun ajaran
$per_tahun = [];
foreach ($hasil['detail'] as $row) {
    $per_tahun[$row['tahun_ajaran']][] = $row;
}

$page_title = 'KHS & IPK';
$active = '/sia/mahasiswa/khs.php';
$menu_items = [
    ['label' => 'Dashboard', 'url' => '/sia/mahasiswa/index.php', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Isi KRS', 'url' => '/sia/mahasiswa/krs.php', 'icon' => 'fas fa-list-alt'],
    ['label' => 'KHS & IPK', 'url' => '/sia/mahasiswa/khs.php', 'icon' => 'fas fa-chart-line'],
];
require __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-lg-6 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= number_format($hasil['ipk'], 2) ?></h3>
                <p>IPK Kumulatif</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
    <div class="col-lg-6 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $hasil['total_sks'] ?></h3>
                <p>Total SKS Ber-nilai</p>
            </div>
            <div class="icon"><i class="fas fa-book"></i></div>
        </div>
    </div>
</div>

<?php if (empty($per_tahun)): ?>
    <div class="alert alert-info">Belum ada data KRS/nilai.</div>
<?php endif; ?>

<?php foreach ($per_tahun as $ta => $rows): ?>
    <div class="card mb-3">
        <div class="card-header"><h3 class="card-title">Kartu Hasil Studi - Tahun Ajaran <?= e($ta) ?></h3></div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead><tr><th>#</th><th>Mata Kuliah</th><th>SKS</th><th>Nilai Angka</th><th>Huruf</th><th>Bobot</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($r['nama_mk']) ?></td>
                        <td><?= e($r['sks']) ?></td>
                        <td><?= $r['nilai_angka'] !== null ? e($r['nilai_angka']) : '-' ?></td>
                        <td><?= $r['nilai_huruf'] !== null ? e($r['nilai_huruf']) : '<span class="badge bg-secondary">Belum dinilai</span>' ?></td>
                        <td><?= $r['bobot'] !== null ? e($r['bobot']) : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
