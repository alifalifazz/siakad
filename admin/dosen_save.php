<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /sia/admin/dosen.php');
    exit;
}

$id = $_POST['id'] ?? null;
$nip = trim($_POST['nip']);
$nama = trim($_POST['nama']);
$username = trim($_POST['username']);

if ($id) {
    $stmt = $pdo->prepare("UPDATE dosen SET nip = ?, nama = ? WHERE id = ?");
    $stmt->execute([$nip, $nama, $id]);
} else {
    $password = $_POST['password'] ?? '';
    if ($password === '') $password = 'password';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'dosen')");
        $stmt->execute([$username, $hash]);
        $user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO dosen (user_id, nip, nama) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $nip, $nama]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Gagal menyimpan data: " . $e->getMessage());
    }
}

header('Location: /sia/admin/dosen.php');
exit;
