<?php $flash = $flash ?? flash_get(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title ?? APP_NAME) ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_BASE ?>/public/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container navwrap">
        <a class="brand" href="index.php?action=home">
            <span class="brand-mark">🐾</span>
            <span>Huellas de Hogar</span>
        </a>

        <button class="nav-toggle" type="button" onclick="toggleMenu()">☰</button>

        <nav class="nav" id="mainNav">
            <a href="index.php?action=home">Inicio</a>
            <a href="index.php?action=animales">Animales</a>
            <?php if (is_logged_in()): ?>
                <a href="index.php?action=perfil">Mi perfil</a>
                <?php if (is_admin()): ?>
                    <a href="index.php?action=admin">Admin</a>
                <?php endif; ?>
                <a href="index.php?action=logout" class="btn btn-outline">Salir</a>
            <?php else: ?>
                <a href="index.php?action=login">Login</a>
                <a href="index.php?action=registro" class="btn btn-outline">Registro</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="main">
    <div class="container">
        <?php if (!empty($flash['success'])): ?>
            <div class="alert alert-success"><?= h($flash['success']) ?></div>
        <?php endif; ?>
        <?php if (!empty($flash['error'])): ?>
            <div class="alert alert-error"><?= h($flash['error']) ?></div>
        <?php endif; ?>