<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Security::e($title ?? APP_NAME) ?> — <?= Security::e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/responsive.css">
</head>
<body>
<nav class="site-nav">
    <div class="nav-inner">
        <a class="nav-brand" href="<?= app_url('/') ?>">🚗 <?= Security::e(APP_NAME) ?></a>
        <button type="button" class="nav-toggle" aria-label="Menu" data-nav-toggle>☰</button>
        <div class="nav-links" data-nav-links>
            <a href="<?= app_url('/') ?>">Home</a>
            <a href="<?= app_url('/cars') ?>">Browse Cars</a>
            <a href="<?= app_url('/blog') ?>">Blog</a>
            <?php if ($authUser): ?>
                <?php if ($authUser['role'] === 'admin'): ?>
                    <a href="<?= app_url('/admin') ?>">Dashboard</a>
                    <a href="<?= app_url('/admin/cars') ?>">Cars</a>
                    <a href="<?= app_url('/admin/orders') ?>">Orders</a>
                <?php else: ?>
                    <a href="<?= app_url('/profile') ?>">My Rentals</a>
                <?php endif; ?>
                <a href="<?= app_url('/profile') ?>">Profile</a>
                <a href="<?= app_url('/logout') ?>">Logout</a>
                <span class="nav-user">Hi, <?= Security::e($authUser['name']) ?></span>
            <?php else: ?>
                <a href="<?= app_url('/login') ?>">Login</a>
                <a class="btn btn-sm" href="<?= app_url('/register') ?>">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="container">
<?php if ($msg = flash('success')): ?>
    <div class="alert alert-success" role="status"><?= Security::e($msg) ?></div>
<?php endif; ?>
