<?php
if (!isset($pageTitle)) $pageTitle = 'Клуб серфинга «Волна»';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> — Клуб серфинга «Волна»</title>
    <meta name="description" content="Клуб серфинга «Волна» — обучение серфингу в Хабаровске, подготовка к океану, серф-туры">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<header class="header">
    <div class="container header-inner">
        <a href="index.php" class="logo">
            <span class="logo-icon">🌊</span>
            <span class="logo-text">Волна</span>
        </a>
        <nav class="nav" id="mainNav">
            <a href="index.php" class="nav-link">Главная</a>
            <a href="formats.php" class="nav-link">Форматы обучения</a>
            <a href="apply.php" class="nav-link">Запись</a>
            <a href="reviews.php" class="nav-link">Отзывы</a>
            <?php if (isLoggedIn()): ?>
                <a href="cabinet.php" class="nav-link">Личный кабинет</a>
                <?php if (isAdmin()): ?>
                    <a href="admin.php" class="nav-link admin-link">Админ</a>
                <?php endif; ?>
                <a href="logout.php" class="nav-link btn-outline">Выход</a>
            <?php else: ?>
                <a href="login.php" class="nav-link">Вход</a>
                <a href="register.php" class="nav-link btn-primary-nav">Регистрация</a>
            <?php endif; ?>
        </nav>
        <button class="burger" id="burger" aria-label="Меню">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
<main class="main">
