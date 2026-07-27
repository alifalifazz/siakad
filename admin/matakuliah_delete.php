<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_role('admin');

$id = $_GET['id'] ?? null;
if ($id) {
    $pdo->prepare("DELETE FROM matakuliah WHERE id = ?")->execute([$id]);
}

header('Location: /sia/admin/matakuliah.php');
exit;
