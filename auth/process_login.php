<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /sia/auth/login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['login_error'] = 'Username dan password wajib diisi.';
    header('Location: /sia/auth/login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Username atau password salah.';
    header('Location: /sia/auth/login.php');
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

// Ambil id profil sesuai role (dipakai untuk query data terkait)
if ($user['role'] === 'mahasiswa') {
    $s = $pdo->prepare("SELECT id, nama FROM mahasiswa WHERE user_id = ?");
    $s->execute([$user['id']]);
    $profile = $s->fetch();
    $_SESSION['mahasiswa_id'] = $profile['id'] ?? null;
    $_SESSION['nama'] = $profile['nama'] ?? $user['username'];
} elseif ($user['role'] === 'dosen') {
    $s = $pdo->prepare("SELECT id, nama FROM dosen WHERE user_id = ?");
    $s->execute([$user['id']]);
    $profile = $s->fetch();
    $_SESSION['dosen_id'] = $profile['id'] ?? null;
    $_SESSION['nama'] = $profile['nama'] ?? $user['username'];
} else {
    $_SESSION['nama'] = 'Administrator';
}

header("Location: /sia/{$user['role']}/index.php");
exit;
