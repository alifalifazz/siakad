<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('mahasiswa');

$mahasiswa_id = $_SESSION['mahasiswa_id'];

$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->execute([$mahasiswa_id]);
$profil = $stmt->fetch();

$hasil_ipk = hitung_ipk($pdo, $mahasiswa_id);

$stmt = $pdo->prepare("SELECT COUNT(*) c FROM krs WHERE mahasiswa_id = ?");
$stmt->execute([$mahasiswa_id]);
$total_krs = $stmt->fetch()['c'];

$page_title = 'Dashboard Mahasiswa';
$active = '/sia/mahasiswa/index.php';
$menu_items = [
    ['label' => 'Dashboard', 'url' => '/sia/mahasiswa/index.php', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Isi KRS', 'url' => '/sia/mahasiswa/krs.php', 'icon' => 'fas fa-list-alt'],
    ['label' => 'KHS & IPK', 'url' => '/sia/mahasiswa/khs.php', 'icon' => 'fas fa-chart-line'],
];
require __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= e($profil['nim']) ?></h3>
                <p>NIM - <?= e($profil['nama']) ?></p>
            </div>
            <div class="icon"><i class="fas fa-id-card"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= number_format($hasil_ipk['ipk'], 2) ?></h3>
                <p>IPK Saat Ini</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $total_krs ?></h3>
                <p>Total Mata Kuliah Diambil</p>
            </div>
            <div class="icon"><i class="fas fa-book"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>Program Studi:</strong> <?= e($profil['prodi']) ?></p>
        <p><strong>Angkatan:</strong> <?= e($profil['angkatan']) ?></p>
        <p><strong>Total SKS Lulus:</strong> <?= $hasil_ipk['total_sks'] ?> SKS</p>
        <a href="/sia/mahasiswa/krs.php" class="btn btn-primary btn-sm"><i class="fas fa-list-alt"></i> Isi KRS</a>
        <a href="/sia/mahasiswa/khs.php" class="btn btn-success btn-sm"><i class="fas fa-chart-line"></i> Lihat KHS & IPK</a>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
