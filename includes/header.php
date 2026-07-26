<?php
/**
 * Template header + sidebar AdminLTE.
 * Variabel yang dipakai (disiapkan sebelum include):
 * - $page_title
 * - $menu_items  : array of ['label' => '', 'url' => '', 'icon' => '']
 */
$role_label = [
    'admin' => 'Admin',
    'dosen' => 'Dosen',
    'mahasiswa' => 'Mahasiswa',
][current_role()] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= e($page_title ?? 'SI Akademik') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/sia/assets/css/style.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link"><?= e($_SESSION['nama'] ?? '') ?> (<?= e($role_label) ?>)</span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/sia/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/sia/<?= e(current_role()) ?>/index.php" class="brand-link">
            <span class="brand-text font-weight-light">SI Akademik</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <?php foreach (($menu_items ?? []) as $item): ?>
                        <li class="nav-item">
                            <a href="<?= e($item['url']) ?>" class="nav-link <?= (($active ?? '') === $item['url']) ? 'active' : '' ?>">
                                <i class="nav-icon <?= e($item['icon']) ?>"></i>
                                <p><?= e($item['label']) ?></p>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </aside>
    <!-- /.sidebar -->

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0"><?= e($page_title ?? '') ?></h1>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
