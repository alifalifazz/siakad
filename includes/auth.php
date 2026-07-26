<?php
// ============================================================
// Helper autentikasi & proteksi role
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_role() {
    return $_SESSION['role'] ?? null;
}

/**
 * Wajibkan login, jika belum login redirect ke halaman login.
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: /sia/auth/login.php');
        exit;
    }
}

/**
 * Wajibkan role tertentu, jika tidak sesuai maka ditolak.
 * @param string|array $roles
 */
function require_role($roles) {
    require_login();
    $roles = is_array($roles) ? $roles : [$roles];
    if (!in_array(current_role(), $roles)) {
        http_response_code(403);
        die('Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}
