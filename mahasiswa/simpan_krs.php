<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_role('mahasiswa');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /sia/mahasiswa/krs.php');
    exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$tahun_ajaran = trim($_POST['tahun_ajaran'] ?? '2025/2026');
$mk_ids = $_POST['mk_id'] ?? [];

// Cari mahasiswa untuk menentukan semester_ke sederhana (dihitung dari angkatan)
$stmt = $pdo->prepare("SELECT angkatan FROM mahasiswa WHERE id = ?");
$stmt->execute([$mahasiswa_id]);
$angkatan = $stmt->fetch()['angkatan'];
$semester_ke = max(1, ((int) date('Y') - (int) $angkatan) * 2 + 1);

// Batas maksimal SKS per tahun ajaran
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(mk.sks), 0) total FROM krs
    JOIN matakuliah mk ON krs.matakuliah_id = mk.id
    WHERE krs.mahasiswa_id = ? AND krs.tahun_ajaran = ?
");
$stmt->execute([$mahasiswa_id, $tahun_ajaran]);
$total_sks_sekarang = (int) $stmt->fetch()['total'];

$insert = $pdo->prepare("
    INSERT IGNORE INTO krs (mahasiswa_id, matakuliah_id, tahun_ajaran, semester_ke)
    VALUES (?, ?, ?, ?)
");

$max_sks = 24;
foreach ($mk_ids as $mk_id) {
    $mk_id = (int) $mk_id;
    $s = $pdo->prepare("SELECT sks FROM matakuliah WHERE id = ?");
    $s->execute([$mk_id]);
    $sks = (int) ($s->fetch()['sks'] ?? 0);

    if ($total_sks_sekarang + $sks > $max_sks) {
        continue; // lewati jika melebihi batas maksimal SKS
    }

    $insert->execute([$mahasiswa_id, $mk_id, $tahun_ajaran, $semester_ke]);
    $total_sks_sekarang += $sks;
}

header('Location: /sia/mahasiswa/krs.php?ta=' . urlencode($tahun_ajaran));
exit;
