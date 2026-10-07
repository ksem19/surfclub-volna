<?php
require_once 'config.php';
$pageTitle = 'Главная';
require_once 'includes/header.php';
?>

<section class="hero">
    <div class="container">
        <h1>Поймай свою волну</h1>
        <p>Клуб серфинга «Волна» — обучение с нуля, подготовка к океану и серф-сообщество в Хабаровске. Инструкторы с опытом, безопасные тренировки и незабываемые туры.</p>
        <div class="hero-buttons">
            <a href="formats.php" class="btn btn-primary">Форматы обучения</a>
            <a href="apply.php" class="btn btn-secondary">Записаться</a>
        </div>
    </div>
</section>

<div class="container">
    <div class="slider-wrap">
        <div class="slider">
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1502680390469-be75c86b636f?w=1200&q=80');">
                <div class="slide-caption">Обучение на доске с нуля</div>
            </div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1455729552865-3658a5d39692?w=1200&q=80');">
                <div class="slide-caption">Подготовка к океанским волнам</div>
            </div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1500375592092-40eb2168fd21?w=1200&q=80');">
                <div class="slide-caption">Серф-туры и кемпы</div>
            </div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1520942702018-0862207248e5?w=1200&q=80');">
                <div class="slide-caption">Сообщество единомышленников</div>
            </div>
        </div>
        <button class="slider-btn prev" aria-label="Назад">‹</button>
        <button class="slider-btn next" aria-label="Вперёд">›</button>
        <div class="slider-dots"></div>
    </div>

    <h2 class="section-title">Почему выбирают «Волну»</h2>
    <p class="section-sub">Всё для комфортного старта и роста в серфинге</p>

    <div class="features">
        <div class="feature">
            <div class="feature-icon">🏄</div>
            <h3>Опытные инструкторы</h3>
            <p>Тренеры с сертификацией и реальным опытом катания на океане</p>
        </div>
        <div class="feature">
            <div class="feature-icon">📋</div>
            <h3>Разные форматы</h3>
            <p>От пробного занятия до интенсивной подготовки к турам</p>
        </div>
        <div class="feature">
            <div class="feature-icon">🌊</div>
            <h3>Безопасность</h3>
            <p>Теория, разминка и контроль на каждом этапе обучения</p>
        </div>
        <div class="feature">
            <div class="feature-icon">💬</div>
            <h3>Сообщество</h3>
            <p>Совместные выезды, отзывы и поддержка после курса</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="reviews.php" class="btn btn-primary">Читать отзывы</a>
        <a href="register.php" class="btn btn-secondary" style="margin-left: 12px;">Создать аккаунт</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
