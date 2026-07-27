<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

$total_mhs = $pdo->query("SELECT COUNT(*) c FROM mahasiswa")->fetch()['c'];
$total_dosen = $pdo->query("SELECT COUNT(*) c FROM dosen")->fetch()['c'];
$total_mk = $pdo->query("SELECT COUNT(*) c FROM matakuliah")->fetch()['c'];
$total_krs = $pdo->query("SELECT COUNT(*) c FROM krs")->fetch()['c'];

$page_title = 'Dashboard Admin';
$active = '/sia/admin/index.php';
$menu_items = [
    ['label' => 'Dashboard', 'url' => '/sia/admin/index.php', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Data Mahasiswa', 'url' => '/sia/admin/mahasiswa.php', 'icon' => 'fas fa-user-graduate'],
    ['label' => 'Data Dosen', 'url' => '/sia/admin/dosen.php', 'icon' => 'fas fa-chalkboard-teacher'],
    ['label' => 'Mata Kuliah', 'url' => '/sia/admin/matakuliah.php', 'icon' => 'fas fa-book'],
];
require __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $total_mhs ?></h3>
                <p>Total Mahasiswa</p>
            </div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $total_dosen ?></h3>
                <p>Total Dosen</p>
            </div>
            <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $total_mk ?></h3>
                <p>Mata Kuliah</p>
            </div>
            <div class="icon"><i class="fas fa-book"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $total_krs ?></h3>
                <p>Total KRS Terdaftar</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
