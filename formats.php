<?php
require_once 'config.php';
$pageTitle = 'Форматы обучения';
$pdo = getDB();
$formats = $pdo->query("SELECT * FROM training_formats WHERE is_active = 1 ORDER BY price")->fetchAll();
require_once 'includes/header.php';
?>

<div class="container">
    <h1 class="section-title">Форматы обучения серфингу</h1>
    <p class="section-sub">Выберите подходящий курс — от первого раза на доске до подготовки к океану</p>

    <div class="cards">
        <?php foreach ($formats as $f): ?>
        <div class="card">
            <span class="level"><?= e($f['level']) ?></span>
            <h3><?= e($f['title']) ?></h3>
            <p><?= e($f['description']) ?></p>
            <p><strong>Длительность:</strong> <?= e($f['duration']) ?></p>
            <div class="price"><?= number_format($f['price'], 0, '', ' ') ?> ₽</div>
            <a href="apply.php?format_id=<?= (int)$f['id'] ?>" class="btn btn-primary btn-sm">Записаться</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
