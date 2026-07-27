<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /sia/admin/mahasiswa.php');
    exit;
}

$id = $_POST['id'] ?? null;
$nim = trim($_POST['nim']);
$nama = trim($_POST['nama']);
$prodi = trim($_POST['prodi']);
$angkatan = (int) $_POST['angkatan'];
$username = trim($_POST['username']);

if ($id) {
    // Update data mahasiswa (username & password tidak diubah dari sini)
    $stmt = $pdo->prepare("UPDATE mahasiswa SET nim = ?, nama = ?, prodi = ?, angkatan = ? WHERE id = ?");
    $stmt->execute([$nim, $nama, $prodi, $angkatan, $id]);
} else {
    $password = $_POST['password'] ?? '';
    if ($password === '') $password = 'password';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'mahasiswa')");
        $stmt->execute([$username, $hash]);
        $user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO mahasiswa (user_id, nim, nama, prodi, angkatan) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $nim, $nama, $prodi, $angkatan]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Gagal menyimpan data: " . $e->getMessage());
    }
}

header('Location: /sia/admin/mahasiswa.php');
exit;
