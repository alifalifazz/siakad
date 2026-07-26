<?php
require_once __DIR__ . '/../includes/auth.php';

if (is_logged_in()) {
    $role = current_role();
    header("Location: /sia/$role/index.php");
    exit;
}

$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Informasi Akademik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/sia/assets/css/style.css">
</head>
<body class="hold-transition login-page bg-gradient-primary">
<div class="login-box">
    <div class="login-logo">
        <b>SI</b>Akademik
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silakan masuk untuk memulai sesi</p>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2"><?= e($error) ?></div>
            <?php endif; ?>

            <form action="/sia/auth/process_login.php" method="POST">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                    <div class="input-group-text"><span class="fas fa-user"></span></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-text"><span class="fas fa-lock"></span></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block w-100">Masuk</button>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-0 text-muted small text-center">
                Demo: admin/password &middot; dosen1/password &middot; mhs1/password
            </p>
        </div>
    </div>
</div>
</body>
</html>
