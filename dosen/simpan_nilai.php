<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_role('dosen');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /sia/dosen/index.php');
    exit;
}

$dosen_id = $_SESSION['dosen_id'];
$mk_id = $_POST['mk_id'];

// Verifikasi mata kuliah milik dosen ini
$stmt = $pdo->prepare("SELECT id FROM matakuliah WHERE id = ? AND dosen_id = ?");
$stmt->execute([$mk_id, $dosen_id]);
if (!$stmt->fetch()) {
    die('Akses ditolak.');
}

$nilai_list = $_POST['nilai'] ?? [];

foreach ($nilai_list as $krs_id => $angka) {
    if ($angka === '' || $angka === null) continue;

    $angka = (float) $angka;
    [$huruf, $bobot] = konversi_nilai($angka);

    // Cek apakah nilai untuk krs_id ini sudah ada
    $stmt = $pdo->prepare("SELECT id FROM nilai WHERE krs_id = ?");
    $stmt->execute([$krs_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE nilai SET nilai_angka=?, nilai_huruf=?, bobot=? WHERE krs_id=?");
        $stmt->execute([$angka, $huruf, $bobot, $krs_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO nilai (krs_id, nilai_angka, nilai_huruf, bobot) VALUES (?, ?, ?, ?)");
        $stmt->execute([$krs_id, $angka, $huruf, $bobot]);
    }
}

header("Location: /sia/dosen/input_nilai.php?mk_id=$mk_id");
exit;
