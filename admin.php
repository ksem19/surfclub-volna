<?php
require_once 'config.php';
if (!isLoggedIn() || !isAdmin()) redirect('login.php');

$pdo = getDB();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['app_id'], $_POST['status'])) {
    $appId = (int)$_POST['app_id'];
    $status = $_POST['status'];
    $allowed = ['Новая', 'Идет обучение', 'Обучение завершено'];
    if (in_array($status, $allowed, true)) {
        $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->execute([$status, $appId]);
        $message = 'Статус заявки #' . $appId . ' обновлён';
    }
}

$filter = $_GET['status'] ?? '';
$sql = "
    SELECT a.*, u.fio, u.login, u.email, u.phone, f.title as format_title, f.price
    FROM applications a
    JOIN users u ON u.id = a.user_id
    JOIN training_formats f ON f.id = a.format_id
";
$params = [];
if ($filter && in_array($filter, ['Новая', 'Идет обучение', 'Обучение завершено'], true)) {
    $sql .= " WHERE a.status = ?";
    $params[] = $filter;
}
$sql .= " ORDER BY a.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$applications = $stmt->fetchAll();

$reviewsCount = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
$usersCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();

$pageTitle = 'Панель администратора';
require_once 'includes/header.php';
?>

<div class="container">
    <h1 class="section-title">Панель администратора</h1>
    <p class="section-sub">Заявки: <?= count($applications) ?> | Пользователи: <?= (int)$usersCount ?> | Отзывы: <?= (int)$reviewsCount ?></p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= e($message) ?></div>
    <?php endif; ?>

    <div style="margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap">
        <a href="admin.php" class="btn btn-sm <?= $filter === '' ? 'btn-primary' : 'btn-secondary' ?>">Все</a>
        <a href="admin.php?status=Новая" class="btn btn-sm <?= $filter === 'Новая' ? 'btn-primary' : 'btn-secondary' ?>">Новые</a>
        <a href="admin.php?status=Идет+обучение" class="btn btn-sm <?= $filter === 'Идет обучение' ? 'btn-primary' : 'btn-secondary' ?>">Идёт обучение</a>
        <a href="admin.php?status=Обучение+завершено" class="btn btn-sm <?= $filter === 'Обучение завершено' ? 'btn-primary' : 'btn-secondary' ?>">Завершённые</a>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Клиент</th>
                    <th>Контакты</th>
                    <th>Формат</th>
                    <th>Дата</th>
                    <th>Оплата</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($applications)): ?>
                <tr><td colspan="8" style="text-align:center">Нет заявок</td></tr>
                <?php else: ?>
                <?php foreach ($applications as $a): ?>
                <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td><?= e($a['fio']) ?><br><small><?= e($a['login']) ?></small></td>
                    <td><?= e($a['email']) ?><br><?= e($a['phone'] ?? '—') ?></td>
                    <td><?= e($a['format_title']) ?><br><small><?= number_format($a['price'], 0, '', ' ') ?> ₽</small></td>
                    <td><?= $a['preferred_date'] ? date('d.m.Y', strtotime($a['preferred_date'])) : '—' ?></td>
                    <td><?= e($a['payment_method']) ?></td>
                    <td><span class="status-badge status-<?= e($a['status']) ?>"><?= e($a['status']) ?></span></td>
                    <td>
                        <form method="post" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                            <input type="hidden" name="app_id" value="<?= (int)$a['id'] ?>">
                            <select name="status" style="padding:6px;border-radius:6px;background:#0a2e36;color:#fff;border:1px solid #0a9396">
                                <option value="Новая" <?= $a['status'] === 'Новая' ? 'selected' : '' ?>>Новая</option>
                                <option value="Идет обучение" <?= $a['status'] === 'Идет обучение' ? 'selected' : '' ?>>Идет обучение</option>
                                <option value="Обучение завершено" <?= $a['status'] === 'Обучение завершено' ? 'selected' : '' ?>>Обучение завершено</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">OK</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
