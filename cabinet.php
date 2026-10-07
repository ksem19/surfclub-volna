<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');
if (isAdmin()) redirect('admin.php');

$pdo = getDB();
$userId = $_SESSION['user_id'];

$apps = $pdo->prepare("
    SELECT a.*, f.title as format_title, f.price 
    FROM applications a 
    JOIN training_formats f ON f.id = a.format_id 
    WHERE a.user_id = ? 
    ORDER BY a.created_at DESC
");
$apps->execute([$userId]);
$applications = $apps->fetchAll();

$pageTitle = 'Личный кабинет';
require_once 'includes/header.php';
?>

<div class="container">
    <h1 class="section-title">Личный кабинет</h1>
    <p class="section-sub">Здравствуйте, <?= e($_SESSION['fio']) ?>!</p>

    <div style="margin-bottom: 24px; text-align: center;">
        <a href="apply.php" class="btn btn-primary">Новая заявка</a>
        <a href="reviews.php" class="btn btn-secondary" style="margin-left:8px">Отзывы</a>
    </div>

    <h2 style="margin-bottom:16px;color:var(--primary-light)">Мои заявки</h2>
    <?php if (empty($applications)): ?>
        <p style="color:var(--text-muted)">У вас пока нет заявок. <a href="apply.php">Записаться на обучение</a></p>
    <?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Формат</th>
                    <th>Дата</th>
                    <th>Оплата</th>
                    <th>Статус</th>
                    <th>Создана</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $a): ?>
                <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td><?= e($a['format_title']) ?></td>
                    <td><?= $a['preferred_date'] ? date('d.m.Y', strtotime($a['preferred_date'])) : '—' ?></td>
                    <td><?= e($a['payment_method']) ?></td>
                    <td><span class="status-badge status-<?= e($a['status']) ?>"><?= e($a['status']) ?></span></td>
                    <td><?= date('d.m.Y H:i', strtotime($a['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
