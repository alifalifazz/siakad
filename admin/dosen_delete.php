<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT user_id FROM dosen WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$row['user_id']]);
    }
}

header('Location: /sia/admin/dosen.php');
exit;
