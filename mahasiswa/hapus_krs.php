<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_role('mahasiswa');

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$krs_id = $_GET['krs_id'] ?? null;

if ($krs_id) {
    // Pastikan KRS ini milik mahasiswa yang login dan belum punya nilai
    $stmt = $pdo->prepare("
        SELECT krs.id FROM krs
        LEFT JOIN nilai n ON n.krs_id = krs.id
        WHERE krs.id = ? AND krs.mahasiswa_id = ? AND n.id IS NULL
    ");
    $stmt->execute([$krs_id, $mahasiswa_id]);
    if ($stmt->fetch()) {
        $pdo->prepare("DELETE FROM krs WHERE id = ?")->execute([$krs_id]);
    }
}

header('Location: /sia/mahasiswa/krs.php');
exit;
