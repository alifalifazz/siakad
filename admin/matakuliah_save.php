<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /sia/admin/matakuliah.php');
    exit;
}

$id = $_POST['id'] ?? null;
$kode_mk = trim($_POST['kode_mk']);
$nama_mk = trim($_POST['nama_mk']);
$sks = (int) $_POST['sks'];
$semester = (int) $_POST['semester'];
$dosen_id = $_POST['dosen_id'] !== '' ? (int) $_POST['dosen_id'] : null;

if ($id) {
    $stmt = $pdo->prepare("UPDATE matakuliah SET kode_mk=?, nama_mk=?, sks=?, semester=?, dosen_id=? WHERE id=?");
    $stmt->execute([$kode_mk, $nama_mk, $sks, $semester, $dosen_id, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO matakuliah (kode_mk, nama_mk, sks, semester, dosen_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$kode_mk, $nama_mk, $sks, $semester, $dosen_id]);
}

header('Location: /sia/admin/matakuliah.php');
exit;
